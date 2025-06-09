#!/bin/bash

# Azure Infrastructure Setup untuk CI/CD
echo "=== Setup Azure Infrastructure untuk CI/CD ==="

# Variabel konfigurasi
RESOURCE_GROUP="presensi-rg"
LOCATION="southeastasia"
ACR_NAME="presensiapp"
MYSQL_SERVER="presensi-mysql-server"
MYSQL_ADMIN_USER="presensi_admin"
MYSQL_ADMIN_PASSWORD="SecurePass123!"
DATABASE_NAME="presensi"

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

# Check if Azure CLI is installed
if ! command -v az &> /dev/null; then
    print_error "Azure CLI tidak terinstall. Install dulu dari: https://docs.microsoft.com/en-us/cli/azure/install-azure-cli"
    exit 1
fi

# Login to Azure
print_status "Login ke Azure..."
az login

# Create Resource Group
print_status "Membuat Resource Group: $RESOURCE_GROUP"
az group create --name $RESOURCE_GROUP --location $LOCATION

# Create Azure Container Registry
print_status "Membuat Azure Container Registry: $ACR_NAME"
az acr create --resource-group $RESOURCE_GROUP --name $ACR_NAME --sku Basic --admin-enabled true

# Get ACR credentials
print_status "Mendapatkan ACR credentials..."
ACR_USERNAME=$(az acr credential show --name $ACR_NAME --query username --output tsv)
ACR_PASSWORD=$(az acr credential show --name $ACR_NAME --query passwords[0].value --output tsv)

# Create Azure Database for MySQL
print_status "Membuat Azure Database for MySQL..."
az mysql flexible-server create \
  --resource-group $RESOURCE_GROUP \
  --name $MYSQL_SERVER \
  --admin-user $MYSQL_ADMIN_USER \
  --admin-password $MYSQL_ADMIN_PASSWORD \
  --sku-name Standard_B1ms \
  --tier Burstable \
  --public-access All \
  --storage-size 20 \
  --location $LOCATION

# Create database
print_status "Membuat database: $DATABASE_NAME"
az mysql flexible-server db create \
  --resource-group $RESOURCE_GROUP \
  --server-name $MYSQL_SERVER \
  --database-name $DATABASE_NAME

# Get database connection details
DB_HOST="$MYSQL_SERVER.mysql.database.azure.com"

# Create Service Principal for GitHub Actions
print_status "Membuat Service Principal untuk GitHub Actions..."
SUBSCRIPTION_ID=$(az account show --query id --output tsv)
SP_OUTPUT=$(az ad sp create-for-rbac --name "presensi-github-actions" \
  --role contributor \
  --scopes /subscriptions/$SUBSCRIPTION_ID/resourceGroups/$RESOURCE_GROUP \
  --json-auth)

print_status "=== Setup Completed! ==="
echo ""
print_warning "SIMPAN INFORMASI BERIKUT UNTUK GITHUB SECRETS:"
echo ""
echo "GitHub Repository Secrets yang perlu ditambahkan:"
echo "================================================"
echo "AZURE_CREDENTIALS = $SP_OUTPUT"
echo ""
echo "ACR_USERNAME = $ACR_USERNAME"
echo "ACR_PASSWORD = $ACR_PASSWORD"
echo ""
echo "DB_HOST = $DB_HOST"
echo "DB_DATABASE = $DATABASE_NAME"
echo "DB_USERNAME = $MYSQL_ADMIN_USER"
echo "DB_PASSWORD = $MYSQL_ADMIN_PASSWORD"
echo ""
echo "LARAVEL_APP_KEY = (Generate dengan: php artisan key:generate --show)"
echo ""
echo "================================================"
echo ""
print_warning "Cara menambahkan secrets ke GitHub:"
echo "1. Buka repository GitHub Anda"
echo "2. Settings → Secrets and variables → Actions"
echo "3. Klik 'New repository secret'"
echo "4. Tambahkan semua secrets di atas"
echo ""
print_status "Setelah itu, push kode ke branch main/master untuk trigger deployment otomatis!"

# Generate Laravel APP_KEY
print_status "Generating Laravel APP_KEY..."
if [ -f "artisan" ]; then
    APP_KEY=$(php artisan key:generate --show)
    echo "LARAVEL_APP_KEY = $APP_KEY"
else
    print_warning "File artisan tidak ditemukan. Generate APP_KEY manual dengan:"
    echo "php artisan key:generate --show"
fi 