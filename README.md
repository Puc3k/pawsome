# PHP
[Trello](https://trello.com/b/os4W8MXa/php)
## Plik konfiguracyjny
/config/.config.php
+ skopiować i usunąć kropkę
+ uzupełnić dane do bazy
+ w repo powinien latać tylko plik .config.php
## Jak zrobić baze?
skopiować to :) (PS: jak się przesunie i najedzie w prawym górnym rogu to jest przycisk)

```
CREATE DATABASE IF NOT EXISTS `pawsome` DEFAULT CHARSET=utf8mb4 COLLATE utf8mb4_general_ci; CREATE TABLE `pawsome`.`breed_images` 
( `id` int NOT NULL, `breed_id` int NOT NULL, `image` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL ) 
ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci; ALTER TABLE `pawsome`.`breed_images` 
ADD PRIMARY KEY (`id`), ADD KEY `breed_image_breed_id_index` (`breed_id`); ALTER TABLE `pawsome`.`breed_images` 
MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9481 ; CREATE TABLE `pawsome`.`breed_list` 
( `id` int NOT NULL, `breed` varchar(255) COLLATE utf8mb4_general_ci NOT NULL, `sub_breed` varchar(255) 
CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 
COLLATE=utf8mb4_general_ci; ALTER TABLE `pawsome`.`breed_list` ADD PRIMARY KEY (`id`); ALTER TABLE `pawsome`.
`breed_list` MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149 ;
```
