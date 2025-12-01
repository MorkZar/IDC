pipeline {
  agent any
  environment {
    IMAGE_NAME = "idc-app:latest"
    # Descomenta y configura si harás push al registry
    # DOCKER_REGISTRY = "registry.example.com/yourproject"
    # DOCKER_USER = credentials('docker-username-id')
    # DOCKER_PASSWORD = credentials('docker-password-id')
  }
  stages {
    stage('Build') {
      steps {
        script {
          if (isUnix()) {
            // Buscar composer.json hasta 3 niveles
            sh '''
              echo "Buscando composer.json..."
              FILE=$(find . -maxdepth 3 -name composer.json | head -n 1 || true)
              if [ -z "$FILE" ]; then
                echo "ERROR: No se encontró composer.json"
                ls -la
                exit 1
              fi
              DIR=$(dirname "$FILE")
              echo "composer.json encontrado en: $DIR"
              cd "$DIR"
              echo "Ejecutando composer install en $(pwd)..."
              composer install --no-interaction --prefer-dist --no-progress
            '''
          } else {
            // Windows (PowerShell)
            bat '''
              powershell -NoProfile -Command ^
                "$file = Get-ChildItem -Path . -Recurse -Filter composer.json -Depth 3 | Select-Object -First 1; ^
                 if (-not $file) { Write-Host 'ERROR: No se encontro composer.json'; dir; exit 1 } ^
                 $dir = Split-Path $file.FullName; Write-Host 'composer.json encontrado en:' $dir; ^
                 Set-Location $dir; Write-Host 'Ejecutando composer install en' (Get-Location); ^
                 composer install --no-interaction --prefer-dist --no-progress"
            '''
          }
        }
      }
    }

    stage('Lint (opcional)') {
      steps {
        script {
          if (isUnix()) {
            sh '''
              # si quieres cambiar el archivo a checar, modifícalo aquí
              FILE=./inicioSesion1.php
              if [ -f "$FILE" ]; then
                echo "Linting $FILE..."
                php -l "$FILE" || true
              else
                echo "Archivo $FILE no encontrado — salteando lint."
              fi
            '''
          } else {
            bat '''
              if exist inicioSesion1.php (
                echo Linting inicioSesion1.php
                php -l inicioSesion1.php || exit 0
              ) else (
                echo inicioSesion1.php no existe — salteando lint
              )
            '''
          }
        }
      }
    }

    stage('Deploy') {
      steps {
        script {
          if (isUnix()) {
            sh '''
              echo "Construyendo imagen Docker..."
              docker build -t ${IMAGE_NAME} .
              # Si necesitas push, descomenta y configura las variables de entorno
              # echo "$DOCKER_PASSWORD" | docker login -u "$DOCKER_USER" --password-stdin $DOCKER_REGISTRY
              # docker tag ${IMAGE_NAME} ${DOCKER_REGISTRY}:${GIT_COMMIT}
              # docker push ${DOCKER_REGISTRY}:${GIT_COMMIT}

              echo "Levantando contenedores con docker-compose..."
              docker-compose up -d --build
            '''
          } else {
            bat '''
              echo Construyendo imagen Docker...
              docker build -t %IMAGE_NAME% .
              rem Si haces push, usa login con credenciales configuradas en Jenkins Credentials
              echo Levantando contenedores con docker-compose...
              docker-compose up -d --build
            '''
          }
        }
      }
    }
  }

  post {
    success {
      echo "Pipeline finalizado correctamente."
    }
    failure {
      echo "Pipeline falló. Revisa los logs."
    }
  }
}
