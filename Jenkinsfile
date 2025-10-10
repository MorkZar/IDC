pipeline{
    agent any
    stages{
        stage('Build'){
            steps{
                echo 'Building...'
            }
        }
        stage('Test'){
            steps{
                echo 'Testing...'
            }
        }
        stage('Deploy'){
            steps{
                sh "docker-compose down -v"
                sh "docker-compose up -d --build"
                echo 'Deploying...'
            }
        }
    }
}