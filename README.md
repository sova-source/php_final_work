# php_final_work
Этот репозиторий содержит итоговое задание по крусу PHP (февраль-март 2020), слушатель Советкин Станислав.
По заданию был реализован сервис загрузки изображений. Точкой входа является файл index.php где без регистрации можно просматривать загруженные изображения всех пользователей.
Для добавления или редактирования изображений необходимо перейти на страницу авторизации (регистрации) admin/auth.php (login: admin, password: admin). После авторизации пользователь попадает на страницу личного кабинета admin/index.php, где он может добавлять новые категории изображений и изображения в них, удалять группы фотографий может только пользователь admin.
При добавлении категории в таблицу photocat добавляется соответствующая запись. Можно добавлять сразу несколько изображений, при добавлении изображений в директорию files помещается исходное изображение и его уменьшенная копия для вывода в браузер в качестве ссылки на оригинал, в таблицу photo добавляется запись с именем большого и маленького изображения, ID пользователя который добавил категорию изображения, тэги, признак видимости и количество просмотров. Изображения из личного кабинета можно удалять и менять их атрибуты видимости, пользователь admin может удалять категории вместе с изображениями.
Для создания требуемых таблиц в базе данных imagedb используйте файл imagedb.sql Наглядная работа сервиса на хостинге http://sovetkin.website.masterhost.tech/index.php
