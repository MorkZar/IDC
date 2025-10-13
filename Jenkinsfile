pipeline{
    agent any
    stages{
        stage('Build'){
            steps{
                echo 'Building...'
                //bat 'composer install'
            }
        }
        stage('Test'){
            steps{
                echo 'Running PHPUnit tests...'
                 bat 'php vendor/bin/phpunit TESTS --log-junit report.xml'
            }
        }
        stage('Deploy'){
            steps{
                echo 'Deploying...'
                 junit 'report.xml'
            }
        }
    }
}