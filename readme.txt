run deploy
sudo docker run --name lavarel --network php-network -p 8000:8000 \
    -e DB_CONNECTION=mysql \
    -e DB_HOST=mysql \
    -e DB_PORT=3306 \
    -e DB_DATABASE=internphp \
    -e DB_USERNAME=root \
    -e DB_PASSWORD=root \
    -e APP_DEBUG=true \
    -e APP_KEY=base64:a8NBA4EDAE7Y/Qftl9xTU7ewB5s5c1t2Ai7Inyi7ffw= \
    khoav952/lavarel-backend:0.4


k$ sudo docker exec -it lavarel bash
root@4cb4df53ee73:/var/www/html# php artisan storage:link