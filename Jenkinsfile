pipeline {
  agent any

  environment {
    IMAGE_NAME = "idc-app:latest"
    COMPOSE_FILE = "docker-compose.yml"
    VERIFY_URL = "http://localhost:8081"
  }

  stages {
    stage('Checkout') {
      steps { checkout scm }
    }

    stage('Install dependencies (Composer in container)') {
      steps {
        // Ejecuta composer dentro de un contenedor para no depender de php local
        bat """
@echo off
echo ==== Composer install via docker container ====
REM usa %cd% para montar el workspace actual en Windows (Docker Desktop)
docker run --rm -v "%cd%":/app -w /app composer:2 composer install --no-interaction --prefer-dist --no-progress
IF %ERRORLEVEL% NEQ 0 (
  echo Composer install fallo.
  exit /b 1
)
echo Composer finished.
"""
      }
    }

    stage('Build Docker image') {
      steps {
        bat """
@echo off
echo ==== Docker build ====
docker build -t %IMAGE_NAME% .
IF %ERRORLEVEL% NEQ 0 (
  echo docker build fallo.
  exit /b 1
)
echo Imagen construida: %IMAGE_NAME%
"""
      }
    }

    stage('Deploy with docker-compose') {
      steps {
        bat """
@echo off
echo ==== docker-compose up ====
docker-compose -f %COMPOSE_FILE% up -d --build
IF %ERRORLEVEL% NEQ 0 (
  echo docker-compose up fallo.
  docker-compose -f %COMPOSE_FILE% ps
  exit /b 1
)
echo docker-compose up OK.
"""
      }
    }

    stage('Verify service (localhost)') {
      steps {
        // Usa PowerShell para realizar reintentos y verificar la URL
        bat """
@echo off
powershell -NoProfile -Command ^
  $url = '${VERIFY_URL}'; ^
  Write-Host 'Verificando' $url ' (esperando hasta 30s)...'; ^
  $ok = $false; ^
  for ($i=0; $i -lt 30; $i++) { ^
    try { ^
      $r = Invoke-WebRequest -UseBasicParsing -Uri $url -TimeoutSec 2; ^
      if ($r.StatusCode -ge 200 -and $r.StatusCode -lt 400) { $ok = $true; break } ^
    } catch { Start-Sleep -Seconds 1 } ^
  }; ^
  if (-not $ok) { Write-Host 'ERROR: el servicio no respondió en' $url 'después de 30s'; docker-compose -f '${COMPOSE_FILE}' ps; exit 1 } ^
  Write-Host 'Servicio OK en' $url; ^
  Write-Host 'Abre en tu navegador:' $url
"""
      }
    }
  }

  post {
    success { echo "Pipeline completado correctamente. Abre ${VERIFY_URL} en tu navegador." }
    failure { echo "Pipeline falló. Revisa los logs y contenedores con 'docker-compose ps'." }
  }
}



