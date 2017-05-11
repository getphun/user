CREATE TABLE IF NOT EXISTS `user` (
    `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(150),
    `fullname` VARCHAR(100),
    `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- If you're using many data user
-- consider using below table instead
-- CREATE TABLE IF NOT EXISTS `user` (
--     `id` INTEGER NOT NULL AUTO_INCREMENT,
--     `name` VARCHAR(50) NOT NULL UNIQUE,
--     `password` VARCHAR(150),
--     `fullname` VARCHAR(100),
--     `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
--     `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
--     
--     PRIMARY KEY(
--         `id`
--     )
-- )
--     PARTITION BY KEY(`id`)
--     PARTITIONS 50;

CREATE TABLE IF NOT EXISTS `user_session` (
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `user` INTEGER NOT NULL,
    `hash` VARCHAR(150) NOT NULL UNIQUE,
    `expired` TIMESTAMP,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    PRIMARY KEY (
        `id`,
        `hash`
    )
)
    PARTITION BY KEY(`hash`)
    PARTITIONS 50;