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
                bat 'C:\\xampp\\php\\php.exe vendor\\bin\\phpunit IDC\\TESTS'
            }
        }
        stage('Deploy'){
            steps{
                echo 'Deploying...'
            }
        }
    }
}