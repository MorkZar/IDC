pipeline {
  agent any
  environment {
    IMAGE_NAME = "idc-app:latest"
    COMPOSE_FILE = "docker-compose.yml"
    VERIFY_URL = "http://localhost:8081"
    // Si quieres hacer push a un registry, define aquí DOCKER_REGISTRY y usa withCredentials en la sección correspondiente
  }

  stages {
    stage('Checkout') {
      steps {
        checkout scm
      }
    }

    stage('Install dependencies (Composer)') {
      steps {
        script {
          // Ejecutar composer dentro de un contenedor para no depender de php en el agente
          sh '''
            echo "Instalando dependencias con composer en contenedor..."
            # monta el workspace en /app y ejecuta composer en ese directorio
            docker run --rm -v "$PWD":/app -w /app composer:2 composer install --no-interaction --prefer-dist --no-progress || exit 1
            echo "Composer terminado."
          '''
        }
      }
    }

    stage('Build Docker image') {
      steps {
        script {
          sh '''
            echo "Construyendo imagen Docker: ${IMAGE_NAME} (contexto: workspace root)..."
            docker build -t ${IMAGE_NAME} .
            echo "Imagen construida: ${IMAGE_NAME}"
          '''
        }
      }
    }

    stage('Deploy with docker-compose') {
      steps {
        script {
          sh '''
            echo "Levantando servicios con docker-compose (archivo: ${COMPOSE_FILE})..."
            docker-compose -f ${COMPOSE_FILE} up -d --build
            echo "docker-compose up finalizado."
          '''
        }
      }
    }

    stage('Verify service') {
      steps {
        script {
          sh '''
            echo "Verificando servicio en ${VERIFY_URL} (esperando hasta 30s)..."
            n=0
            until curl -sSf "${VERIFY_URL}" > /dev/null; do
              n=$((n+1))
              if [ $n -gt 30 ]; then
                echo "ERROR: el servicio no respondió en ${VERIFY_URL} después de 30 segundos."
                docker-compose ps
                exit 1
              fi
              sleep 1
            done
            echo "Servicio OK en ${VERIFY_URL}"
            echo "Abre en tu navegador: ${VERIFY_URL}"
          '''
        }
      }
    }
  }

  post {
    success {
      echo "Pipeline completado correctamente. Abre ${VERIFY_URL} en tu navegador."
    }
    failure {
      echo "Pipeline falló. Revisa los logs y el estado de los contenedores con 'docker-compose ps'."
    }
  }
}


