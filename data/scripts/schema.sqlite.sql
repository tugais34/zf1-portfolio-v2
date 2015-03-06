CREATE TABLE `user` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    `login` VARCHAR(256) NOT NULL,
    `password` VARCHAR(256) NOT NULL,
    `email` VARCHAR(256) NOT NULL,
    `linkedin_id` VARCHAR(256) NULL,
    `viadeo_id` VARCHAR(256) NULL,
    `created` DATETIME NOT NULL
);
 
CREATE INDEX "pk_user_id" ON "user" ("id");

CREATE TABLE `skill` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    `label` VARCHAR(256) NOT NULL,
    `level` INTEGER NOT NULL,
    `description` TEXT,
    `type` INTEGER NOT NULL,
    `years` INTEGER
);

CREATE INDEX "pk_skill_id" ON "skill" ("id");

CREATE TABLE `progress` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    `label` VARCHAR(256) NOT NULL,
    `start_date` DATETIME NOT NULL,
    `end_date` DATETIME,
    `type` INTEGER NOT NULL,
    `now` INTEGER,
    `description` TEXT,
    `place` VARCHAR(256) NOT NULL
);

CREATE INDEX "pk_progress_id" ON "progress" ("id");

CREATE TABLE `work` (
    `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    `label` VARCHAR(256) NOT NULL,
    `client` VARCHAR(256),
    `url` TEXT NOT NULL,
    `type` INTEGER NOT NULL,
    `upload_dir` TEXT,
    `release_date` DATETIME NOT NULL,
    `description` TEXT
);

CREATE INDEX "pk_work_id" ON "work" ("id");

CREATE TABLE `technologie` (
  `id` INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
  `label` VARCHAR(256) NOT NULL
);

CREATE INDEX "pk_technologie_id" ON "technologie" ("id");

CREATE TABLE `work_technologie` (
  `work_id` INTEGER NOT NULL,
  `technologie_id` INTEGER NOT NULL
);

CREATE UNIQUE INDEX "pk_work_technologie_id" ON "work_technologie" ("work_id", "technologie_id");
