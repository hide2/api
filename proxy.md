nginx
========
```
#Mac
brew install nginx
nginx
nginx -t
nginx -s reload
vi /usr/local/etc/nginx/nginx.conf

#CentOS
sudo yum install -y nginx
systemctl start nginx.service
systemctl enable nginx.service
nginx -t
nginx -s reload
vi /etc/nginx/nginx.conf
```

AWS
========
1. CloudFront serves your content over HTTPS only to clients that support SNI. Older browsers and other clients that do not support SNI can not access your content over HTTPS.
2. NLB use http instead of https

proxy https
========
```
    server {
        listen 80;
        ssl on;
        ssl_certificate      cert/xxx.pem;
        ssl_certificate_key  cert/xxx.key;
        location / {
            proxy_pass         https://www.xxx.com;
            proxy_set_header   Host             $host;
            proxy_set_header   X-Real-IP        $remote_addr;
            proxy_set_header   X-Forwarded-For  $proxy_add_x_forwarded_for;
        }
    }
```

proxy websocket
========
```
    map $http_upgrade $connection_upgrade {
        default upgrade;
        '' close;
    }
 
    server {
        listen 443;
        ssl on;
        ssl_certificate      cert/xxx.pem;
        ssl_certificate_key  cert/xxx.key;
        location / {
            proxy_pass https://ws.xxx.com;
            proxy_http_version 1.1;
            proxy_set_header Upgrade $http_upgrade;
            proxy_set_header Connection $connection_upgrade;
        }
    }
```