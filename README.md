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
CREATE TABLE breed_list (
  id int(11) NOT NULL AUTO_INCREMENT,
 breed varchar(255) NOT NULL,
 sub_breed varchar(255) DEFAULT NULL,
 PRIMARY KEY (id)
) ENGINE=InnoDB AUTO_INCREMENT=296 DEFAULT CHARSET=utf8mb4;

CREATE TABLE users (
 id int(11) NOT NULL AUTO_INCREMENT,
 username varchar(50) NOT NULL,
 email varchar(100) NOT NULL,
 password varchar(255) NOT NULL,
 avatar varchar(255) DEFAULT NULL,
 role varchar(255) NOT NULL DEFAULT 'user',
 PRIMARY KEY (id),
 UNIQUE KEY unique_email (email),
 KEY username_index (username),
 KEY password_index (password)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;

CREATE TABLE breed_images (
 id int(11) NOT NULL AUTO_INCREMENT,
 breed_id int(11) NOT NULL,
 image varchar(255) DEFAULT NULL,
 PRIMARY KEY (id),
 KEY breed_image_breed_id_index (breed_id),
 CONSTRAINT breed_images_breed_id FOREIGN KEY (breed_id) REFERENCES breed_list (id)
) ENGINE=InnoDB AUTO_INCREMENT=18978 DEFAULT CHARSET=utf8mb4;

CREATE TABLE quizzes (
 id int(11) NOT NULL AUTO_INCREMENT,
 user_id int(11) DEFAULT 0,
 winner_id int(11) NOT NULL,
 PRIMARY KEY (id),
 KEY user_id_index (user_id),
 KEY winner_id (winner_id),
 CONSTRAINT winnerd_id_breed_images_id FOREIGN KEY (winner_id) REFERENCES breed_images (id)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4;

```
