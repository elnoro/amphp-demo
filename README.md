**Quickshare** (минимальный проект для демонстрации работы amphp)

Состоит из одной страницы, которая содержит в себе форму для добавления ссылок + список добавленных ссылок.
Список ссылок может храниться как в Redis (RedisLinkRepository), так и в памяти приложения.

**Ссылки**

[Как работают корутины в PHP](https://habr.com/ru/post/164173/)
[Building a TCP chat with amphp](https://amphp.org/getting-started/tcp-chat/)

**Запуск проекта**

Через php (только версия без Redis):
`WEB_APP_PORT=9999 php index.php`

Через docker-compose:
`docker-compose up -d`

На Google Cloud (нужны утилиты skaffold и gcloud):
`$ ./enable-cluser.sh && skaffold deploy` 

Если кластер не был создан заранее, раскомментируйте закомментированные строчки в `enable-cluster.sh`
