
#
# Extend table structure of table 'sys_file'
#
CREATE TABLE sys_file (
	cloudinary_failed tinyint(4) DEFAULT '0' NOT NULL,
	cloudinary_public_id text DEFAULT NULL,
	cloudinary_url text DEFAULT NULL,
);
