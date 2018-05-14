nginx
========
```
brew install nginx
vi /usr/local/etc/nginx/nginx.conf
```

sign
========
```
openssl genrsa -des3 -out ssl.key 1024
openssl rsa -in ssl.key -out test.key
openssl req -new -key test.key -out test.csr
openssl x509 -req -days 3650 -in test.csr -signkey test.key -out test.crt
```

https
========
```
	#listen 443;
    #ssl on;
    #ssl_certificate /Users/hide2/projects/nginx/test.crt;
    #ssl_certificate_key /Users/hide2/projects/nginx/test.key;
    #ssl_session_timeout 5m;

	location / {
            proxy_redirect     off;
            proxy_set_header   Host             $host;
            proxy_set_header   X-Real-IP        $remote_addr;
            proxy_set_header   X-Forwarded-For  $proxy_add_x_forwarded_for;
            proxy_pass         https://www.xxx.com;
        }
```

websocket
========
```
    map $http_upgrade $connection_upgrade {
        default upgrade;
        '' close;
    }
 
    upstream websocket {
        server 127.0.0.1:2000;
    }
 
    server {
        listen 3000;
        location / {
            proxy_pass http://websocket;
            proxy_http_version 1.1;
            proxy_set_header Upgrade $http_upgrade;
            proxy_set_header Connection $connection_upgrade;
        }
    }
```