CREATE TABLE `users` (
    `id` INT AUTO_INCREMENT,
    `email` VARCHAR(255) UNIQUE NOT NULL,
    `username` VARCHAR(255) NOT NULL,
    `$hash_pass` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`)
);