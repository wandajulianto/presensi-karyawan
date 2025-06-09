#!/bin/bash

# Azure App Service Debug & Manual Setup Script
echo "=== Azure App Service Debug & Setup ==="

APP_NAME="presensi-karyawan"
RESOURCE_GROUP="KSPPS_BMT_NB"

# Check current app settings
echo "1. Checking current app settings..."
az webapp config appsettings list --name $APP_NAME --resource-group $RESOURCE_GROUP --query "[].{Name:name, Value:value}" --output table

echo ""
echo "2. Setting required environment variables..."

# Set all required Laravel environment variables
az webapp config appsettings set --name $APP_NAME --resource-group $RESOURCE_GROUP --settings \
  APP_ENV=production \
  APP_DEBUG=false \
  APP_TIMEZONE="Asia/Jakarta" \
  APP_URL="https://presensi-karyawan-heg5badjhpb6e0gc.indonesiacentral-01.azurewebsites.net" \
  DB_CONNECTION=mysql \
  SESSION_DRIVER=database \
  CACHE_STORE=database \
  QUEUE_CONNECTION=database

echo ""
echo "3. Checking PHP version and extensions..."
az webapp config show --name $APP_NAME --resource-group $RESOURCE_GROUP --query "{phpVersion:phpVersion, platform:platform}" --output table

echo ""
echo "4. Setting PHP version to 8.2..."
az webapp config set --name $APP_NAME --resource-group $RESOURCE_GROUP --php-version "8.2"

echo ""
echo "5. Checking deployment logs..."
az webapp log tail --name $APP_NAME --resource-group $RESOURCE_GROUP --timeout 30

echo ""
echo "6. Manual restart app..."
az webapp restart --name $APP_NAME --resource-group $RESOURCE_GROUP

echo ""
echo "=== Setup completed! ==="
echo "Wait 2-3 minutes, then test these URLs:"
echo "1. Main app: https://presensi-karyawan-heg5badjhpb6e0gc.indonesiacentral-01.azurewebsites.net/"
echo "2. Health check: https://presensi-karyawan-heg5badjhpb6e0gc.indonesiacentral-01.azurewebsites.net/deploy/status" 