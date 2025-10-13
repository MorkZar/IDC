pipeline{
    agent any
    stages{
        stage('Build'){
            steps{
                echo 'Building...'
                 // Instalar dependencias de PHP
                bat 'composer install'
            }
        }
        stage('Test'){
            steps{
                echo 'Running PHPUnit tests...'
                 bat 'vendor\\bin\\phpunit.bat IDC\\TESTS'
            }
        }
        stage('Deploy'){
            steps{
                echo 'Deploying...'
            }
        }
    }
}