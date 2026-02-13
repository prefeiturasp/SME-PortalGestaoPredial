docker_compose('docker-compose.yml')
docker_build('wordpress/gestpred', '.',
  live_update = [
    sync('.', '/var/www/html')
  ])