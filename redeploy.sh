#!/bin/bash

# Script untuk cleanup dan redeploy aplikasi
echo "=== Cleanup dan Redeploy Aplikasi Presensi ==="

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

# Stop dan remove semua container
print_status "Stopping dan removing all containers..."
docker-compose down -v --remove-orphans

# Remove unused images
print_status "Cleaning up Docker images..."
docker image prune -f

# Remove dangling volumes
print_status "Cleaning up volumes..."
docker volume prune -f

# Clear build cache
print_status "Clearing Docker build cache..."
docker builder prune -f

# Cek file .env
if [ ! -f .env ]; then
    print_warning "File .env tidak ditemukan. Membuat dari .env.example..."
    cp .env.example .env
    
    print_warning "Silakan edit file .env sesuai konfigurasi Anda:"
    print_warning "Khususnya untuk:"
    echo "  - APP_URL=http://$(curl -s http://ipecho.net/plain || echo 'YOUR_SERVER_IP')"
    echo "  - Database credentials (jika diperlukan)"
    echo "  - Mail configuration (jika diperlukan)"
    echo ""
    read -p "Tekan Enter setelah selesai mengedit .env atau jika ingin menggunakan default..."
fi

# Jalankan deployment
print_status "Starting fresh deployment..."
./deploy.sh 