#!/bin/bash

# SSL Setup script menggunakan Let's Encrypt
echo "=== Setup SSL Certificate dengan Let's Encrypt ==="

# Warna untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m'

print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Minta input domain
read -p "Masukkan domain Anda (contoh: presensi.yourdomain.com): " DOMAIN
read -p "Masukkan email untuk Let's Encrypt: " EMAIL

if [ -z "$DOMAIN" ] || [ -z "$EMAIL" ]; then
    print_error "Domain dan email harus diisi!"
    exit 1
fi

print_status "Setting up SSL untuk domain: $DOMAIN"
print_status "Email: $EMAIL"

# Install certbot
print_status "Installing certbot..."
sudo apt update
sudo apt install -y certbot python3-certbot-nginx

# Stop nginx container sementara
print_status "Stopping nginx container..."
docker-compose stop nginx-proxy

# Generate certificate
print_status "Generating SSL certificate..."
sudo certbot certonly --standalone -d $DOMAIN --email $EMAIL --agree-tos --non-interactive

if [ $? -ne 0 ]; then
    print_error "Gagal generate SSL certificate. Pastikan:"
    echo "  - Domain sudah pointing ke IP server ini"
    echo "  - Port 80 dan 443 tidak diblock firewall"
    echo "  - Domain bisa diakses dari internet"
    exit 1
fi

# Buat nginx config dengan SSL
print_status "Creating nginx config with SSL..."
cat > nginx-ssl.conf << EOF
events {
    worker_connections 1024;
}

http {
    include /etc/nginx/mime.types;
    default_type application/octet-stream;

    # Logging
    access_log /var/log/nginx/access.log;
    error_log /var/log/nginx/error.log;

    # Gzip compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1000;
    gzip_types text/plain application/xml text/css text/js text/xml application/javascript;

    # Rate limiting
    limit_req_zone \$binary_remote_addr zone=api:10m rate=10r/s;

    upstream app {
        server app:8000;
    }

    # Redirect HTTP to HTTPS
    server {
        listen 80;
        server_name $DOMAIN;
        return 301 https://\$server_name\$request_uri;
    }

    # HTTPS server
    server {
        listen 443 ssl http2;
        server_name $DOMAIN;
        
        # SSL configuration
        ssl_certificate /etc/letsencrypt/live/$DOMAIN/fullchain.pem;
        ssl_certificate_key /etc/letsencrypt/live/$DOMAIN/privkey.pem;
        
        # SSL security settings
        ssl_protocols TLSv1.2 TLSv1.3;
        ssl_ciphers ECDHE-RSA-AES256-GCM-SHA512:DHE-RSA-AES256-GCM-SHA512:ECDHE-RSA-AES256-GCM-SHA384:DHE-RSA-AES256-GCM-SHA384;
        ssl_prefer_server_ciphers off;
        ssl_session_cache shared:SSL:10m;
        ssl_session_timeout 10m;
        
        # Security headers
        add_header X-Frame-Options "SAMEORIGIN" always;
        add_header X-XSS-Protection "1; mode=block" always;
        add_header X-Content-Type-Options "nosniff" always;
        add_header Referrer-Policy "no-referrer-when-downgrade" always;
        add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
        add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

        # Client max body size
        client_max_body_size 100M;

        location / {
            proxy_pass http://app;
            proxy_set_header Host \$host;
            proxy_set_header X-Real-IP \$remote_addr;
            proxy_set_header X-Forwarded-For \$proxy_add_x_forwarded_for;
            proxy_set_header X-Forwarded-Proto \$scheme;
            
            # Timeouts
            proxy_connect_timeout 60s;
            proxy_send_timeout 60s;
            proxy_read_timeout 60s;
        }

        # Health check endpoint
        location /health {
            access_log off;
            return 200 "healthy\n";
            add_header Content-Type text/plain;
        }
    }
}
EOF

# Update docker-compose.yml untuk SSL
print_status "Updating docker-compose.yml for SSL..."
cat > docker-compose-ssl.yml << EOF
version: '3.8'

services:
  app:
    build: .
    container_name: presensi-app
    restart: unless-stopped
    ports:
      - "8000:8000"
    volumes:
      - ./storage:/var/www/html/storage
      - ./bootstrap/cache:/var/www/html/bootstrap/cache
    environment:
      - APP_ENV=production
      - APP_DEBUG=false
      - APP_URL=https://$DOMAIN
      - DB_HOST=mysql
      - DB_DATABASE=presensi
      - DB_USERNAME=presensi_user
      - DB_PASSWORD=secure_password_123
    depends_on:
      - mysql
    networks:
      - presensi-network

  mysql:
    image: mysql:8.0
    container_name: presensi-mysql
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: presensi
      MYSQL_USER: presensi_user
      MYSQL_PASSWORD: secure_password_123
      MYSQL_ROOT_PASSWORD: root_password_123
    volumes:
      - mysql_data:/var/lib/mysql
    ports:
      - "3306:3306"
    networks:
      - presensi-network

  nginx-proxy:
    image: nginx:alpine
    container_name: presensi-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./nginx-ssl.conf:/etc/nginx/nginx.conf
      - /etc/letsencrypt:/etc/letsencrypt:ro
    depends_on:
      - app
    networks:
      - presensi-network

volumes:
  mysql_data:

networks:
  presensi-network:
    driver: bridge
EOF

# Restart dengan SSL
print_status "Restarting containers with SSL..."
docker-compose -f docker-compose-ssl.yml up -d

# Setup auto-renewal
print_status "Setting up SSL auto-renewal..."
(crontab -l 2>/dev/null; echo "0 12 * * * /usr/bin/certbot renew --quiet && docker-compose -f /path/to/your/project/docker-compose-ssl.yml restart nginx-proxy") | crontab -

print_status "=== SSL Setup Completed! ==="
echo ""
echo "Aplikasi Anda sekarang dapat diakses dengan SSL di:"
echo "  - https://$DOMAIN"
echo ""
print_warning "SSL certificate akan diperpanjang otomatis setiap hari pada jam 12:00"
print_warning "Pastikan untuk update crontab path sesuai dengan lokasi project Anda" 