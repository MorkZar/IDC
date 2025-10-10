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
                // Para PHP: ejecuta pruebas PHPUnit
                bat '"%PHP_PATH%" vendor\\bin\\phpunit tests\\conectiontest.php'
            }
        }
        stage('Deploy'){
            steps{
                echo 'Deploying...'
            }
        }
    }
}