CREATE TABLE IF NOT EXISTS `user` (
    `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `password` VARCHAR(150),
    `fullname` VARCHAR(100),
    -- 0 Deleted ( We're not going to remove user data permanetly )
    -- 1 Unverified
    -- 2 Verified
    -- 3 Official
    `status` TINYINT DEFAULT 1,
    `updated` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS `user_session` (
    `id` INTEGER NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `user` INTEGER NOT NULL,
    `hash` VARCHAR(150) NOT NULL UNIQUE,
    `expired` TIMESTAMP,
    `created` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
CREATE INDEX `by_user` ON `user_session` ( `user` );
