#!/bin/bash

# Deployment script untuk aplikasi Laravel Presensi
echo "=== Memulai Deployment Aplikasi Presensi ==="

# Warna untuk output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Function untuk print dengan warna
print_status() {
    echo -e "${GREEN}[INFO]${NC} $1"
}

print_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

print_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Cek apakah Docker terinstall
if ! command -v docker &> /dev/null; then
    print_error "Docker tidak terinstall. Silakan install Docker terlebih dahulu."
    exit 1
fi

# Cek apakah Docker Compose terinstall
if ! command -v docker-compose &> /dev/null; then
    print_error "Docker Compose tidak terinstall. Silakan install Docker Compose terlebih dahulu."
    exit 1
fi

# Buat .env file jika belum ada
if [ ! -f .env ]; then
    print_warning "File .env tidak ditemukan. Membuat dari .env.example..."
    cp .env.example .env
    
    # Generate app key
    print_status "Generating APP_KEY..."
    APP_KEY=$(docker run --rm -v $(pwd):/app -w /app php:8.2-cli php artisan key:generate --show)
    sed -i "s/APP_KEY=/APP_KEY=$APP_KEY/" .env
    
    print_warning "Silakan edit file .env sesuai dengan konfigurasi Anda."
    print_warning "Khususnya untuk:"
    echo "  - APP_URL (ganti dengan domain/IP VPS Anda)"
    echo "  - Database credentials (jika diperlukan)"
    echo "  - Mail configuration (jika diperlukan)"
    echo ""
    read -p "Tekan Enter setelah selesai mengedit .env..."
fi

# Buat direktori yang diperlukan
print_status "Membuat direktori yang diperlukan..."
mkdir -p storage/logs
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p bootstrap/cache

# Set permission
print_status "Setting permission..."
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Stop container yang sedang berjalan (jika ada)
print_status "Stopping existing containers..."
docker-compose down

# Build dan start container
print_status "Building dan starting containers..."
docker-compose up --build -d

# Wait for containers to be ready
print_status "Waiting for containers to be ready..."
sleep 10

# Check if app container is running
print_status "Checking if app container is running..."
if ! docker-compose ps | grep -q "presensi-app.*Up"; then
    print_error "App container failed to start! Checking logs..."
    docker-compose logs app
    exit 1
fi

# Wait for database to be ready
print_status "Waiting for database to be ready..."
for i in {1..30}; do
    if docker-compose exec mysql mysql -u presensi_user -psecure_password_123 -e "SELECT 1" presensi >/dev/null 2>&1; then
        print_status "Database is ready!"
        break
    fi
    if [ $i -eq 30 ]; then
        print_error "Database failed to start after 30 attempts"
        docker-compose logs mysql
        exit 1
    fi
    echo "Waiting for database... ($i/30)"
    sleep 2
done

# Run database migration
print_status "Running database migrations..."
docker-compose exec app php artisan migrate --force

# Clear cache
print_status "Clearing application cache..."
docker-compose exec app php artisan config:clear
docker-compose exec app php artisan cache:clear
docker-compose exec app php artisan route:clear
docker-compose exec app php artisan view:clear

# Optimize for production
print_status "Optimizing for production..."
docker-compose exec app php artisan config:cache
docker-compose exec app php artisan route:cache
docker-compose exec app php artisan view:cache

# Check if everything is running
print_status "Checking container status..."
docker-compose ps

# Get the server IP
SERVER_IP=$(curl -s http://ipecho.net/plain)

print_status "=== Deployment Completed! ==="
echo ""
echo "Aplikasi Anda sekarang dapat diakses di:"
echo "  - http://localhost (jika di server lokal)"
echo "  - http://$SERVER_IP (jika di VPS)"
echo ""
print_warning "Pastikan firewall mengizinkan traffic pada port 80 dan 443"
print_warning "Untuk production, setup SSL certificate menggunakan Let's Encrypt"

# Show logs
echo ""
read -p "Apakah Anda ingin melihat logs aplikasi? (y/n): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    docker-compose logs -f app
fi 