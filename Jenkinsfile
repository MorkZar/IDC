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