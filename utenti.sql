# Privilegi per `commenter`@`%`

GRANT SELECT ON *.* TO 'commenter'@'%';

GRANT INSERT ON `progetto`.* TO 'commenter'@'%';


# Privilegi per `commenter`@`localhost`

GRANT USAGE ON *.* TO 'commenter'@'localhost';

GRANT INSERT ON `progetto`.* TO 'commenter'@'localhost';


# Privilegi per `reader`@`%`

GRANT SELECT ON *.* TO 'reader'@'%';

GRANT SELECT ON `progetto`.* TO 'reader'@'%';


# Privilegi per `root`@`127.0.0.1`

GRANT ALL PRIVILEGES ON *.* TO 'root'@'127.0.0.1' WITH GRANT OPTION;


# Privilegi per `root`@`::1`

GRANT ALL PRIVILEGES ON *.* TO 'root'@'::1' WITH GRANT OPTION;


# Privilegi per `root`@`localhost`

GRANT ALL PRIVILEGES ON *.* TO 'root'@'localhost' WITH GRANT OPTION;

GRANT PROXY ON ''@'%' TO 'root'@'localhost' WITH GRANT OPTION;


# Privilegi per `writer`@`%`

GRANT SELECT, INSERT, UPDATE, DELETE ON *.* TO 'writer'@'%';

GRANT INSERT ON `progetto`.`Cliente` TO 'writer'@'%';
