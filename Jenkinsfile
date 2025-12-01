pipeline {
  agent any
  environment {
    IMAGE_NAME = "idc-app:latest"
    COMPOSE_FILE = "docker-compose.yml"
    # Ajusta si quieres otro nombre/registro:
    # DOCKER_REGISTRY = "registry.example.com/yourproject"
  }

  stages {
    stage('Build - Composer') {
      steps {
        script {
          if (isUnix()) {
            sh '''
set -e
echo "Comprobando php..."
if ! command -v php >/dev/null 2>&1; then
  echo "ERROR: php no está instalado en este agente. Instálalo o usa un agente que tenga php."
  exit 1
fi

# Instalar composer si no existe
if ! command -v composer >/dev/null 2>&1; then
  echo "Composer no encontrado. Instalando composer..."
  php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
  php composer-setup.php --install-dir=/usr/local/bin --filename=composer
  rm composer-setup.php
  echo "Composer instalado."
else
  echo "Composer ya presente."
fi

# Buscar composer.json (hasta 3 niveles)
FILE=$(find . -maxdepth 3 -name composer.json | head -n 1 || true)
if [ -z "$FILE" ]; then
  echo "ERROR: No se encontró composer.json en el repo."
  ls -la
  exit 1
fi
DIR=$(dirname "$FILE")
echo "composer.json encontrado en: $DIR"
cd "$DIR"
echo "Ejecutando composer install en $(pwd)..."
composer install --no-interaction --prefer-dist --no-progress
echo "Dependencias instaladas."
'''
          } else {
            // Windows agent
            bat '''
@echo off
REM Verificar php
where php >nul 2>nul
if %errorlevel% neq 0 (
  echo ERROR: php no esta instalado en este agente. Instalara composer requiere php.
  exit /b 1
)

REM Instalar composer si no existe
where composer >nul 2>nul
if %errorlevel% neq 0 (
  echo Composer no encontrado. Instalando composer.phar...
  powershell -NoProfile -Command "Invoke-WebRequest -Uri https://getcomposer.org/installer -OutFile composer-setup.php"
  php composer-setup.php
  if exist composer.phar (
    move /Y composer.phar composer
    echo Composer instalado como 'composer' en el workspace.
    REM opcional: mover a un PATH global si el agente lo permite
  ) else (
    echo ERROR: composer.phar no se generó.
    exit /b 1
  )
) else (
  echo Composer ya presente.
)

REM Buscar composer.json recursivamente
set "FILE="
for /R %%f in (composer.json) do (
  set "FILE=%%f"
  goto :found
)
echo ERROR: No se encontro composer.json
dir
exit /b 1

:found
echo composer.json encontrado en %FILE%
for %%a in ("%FILE%") do set "DIR=%%~dpa"
cd /d "%DIR%"
echo Ejecutando composer install en %CD%
composer install --no-interaction --prefer-dist --no-progress
echo Dependencias instaladas.
'''
          }
        }
      }
    }

    stage('Build Docker Image') {
      steps {
        script {
          if (isUnix()) {
            sh '''
set -e
echo "Construyendo imagen Docker ${IMAGE_NAME} (contexto: repo root)..."
docker build -t ${IMAGE_NAME} .
echo "Imagen construida: ${IMAGE_NAME}"
'''
          } else {
            bat '''
echo Construyendo imagen Docker %IMAGE_NAME% (contexto: repo root)...
docker build -t %IMAGE_NAME% .
echo Imagen construida: %IMAGE_NAME%
'''
          }
        }
      }
    }

    stage('Deploy - docker-compose') {
      steps {
        script {
          if (isUnix()) {
            sh '''
set -e
echo "Levantando servicios con docker-compose (archivo: ${COMPOSE_FILE})..."
docker-compose -f ${COMPOSE_FILE} up -d --build
echo "Servicios levantados."
'''
          } else {
            bat '''
echo Levantando servicios con docker-compose (archivo: %COMPOSE_FILE%)...
docker-compose -f %COMPOSE_FILE% up -d --build
echo Servicios levantados.
'''
          }
        }
      }
    }

    stage('Verify service') {
      steps {
        script {
          if (isUnix()) {
            sh '''
set -e
URL="http://localhost:8081"
echo "Comprobando $URL (esperando hasta 30s)..."
n=0
until curl -sSf "$URL" > /dev/null; do
  n=$((n+1))
  if [ $n -gt 30 ]; then
    echo "ERROR: el servicio no respondió en $URL después de 30 segundos."
    docker-compose ps
    exit 1
  fi
  sleep 1
done
echo "Servicio OK en $URL"
echo "Abre en tu navegador: $URL"
'''
          } else {
            bat '''
powershell -NoProfile -Command ^
  $url = "http://localhost:8081"; ^
  Write-Host "Comprobando $url (esperando hasta 30s)..."; ^
  $ok = $false; ^
  for ($i=0; $i -lt 30; $i++) { ^
    try { ^
      $r = Invoke-WebRequest -UseBasicParsing -Uri $url -TimeoutSec 2; ^
      if ($r.StatusCode -ge 200 -and $r.StatusCode -lt 400) { $ok = $true; break } ^
    } catch { Start-Sleep -Seconds 1 } ^
  }; ^
  if (-not $ok) { Write-Host "ERROR: el servicio no respondió en $url después de 30s"; docker-compose ps; exit 1 } ^
  Write-Host "Servicio OK en $url"; Write-Host "Abre en tu navegador: $url"
'''
          }
        }
      }
    }
  }

  post {
    success {
      echo "Pipeline finalizado correctamente. Abre http://localhost:8081 en tu navegador."
    }
    failure {
      echo "Pipeline falló. Revisa los logs y el estado de los contenedores (docker-compose ps)."
    }
  }
}
