-- cPanel mysql backup
GRANT USAGE ON *.* TO 'ankorcom'@'localhost' IDENTIFIED BY PASSWORD '*C4FB4252434008E7A1E12BD71F866EBD4306245A';
GRANT ALL PRIVILEGES ON `ankorcom\_site`.* TO 'ankorcom'@'localhost';
GRANT ALL PRIVILEGES ON `ankorcom\_ankorco`.* TO 'ankorcom'@'localhost';
GRANT ALL PRIVILEGES ON `ankorcom\_%`.* TO 'ankorcom'@'localhost';
GRANT USAGE ON *.* TO 'ankorcom_ankorco'@'localhost' IDENTIFIED BY PASSWORD '*E1EA251A02DB7FA54F7AC9A6861308E560E80EF9';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, REFERENCES, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES, EXECUTE, CREATE VIEW, SHOW VIEW, CREATE ROUTINE, ALTER ROUTINE ON `ankorcom\_ankorco`.* TO 'ankorcom_ankorco'@'localhost';
GRANT USAGE ON *.* TO 'ankorcom_user'@'localhost' IDENTIFIED BY PASSWORD '*63C330A4E7304FF6BAFB56CB5C4A31F18533DF2A';
GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, REFERENCES, INDEX, ALTER, CREATE TEMPORARY TABLES, LOCK TABLES, EXECUTE, CREATE VIEW, SHOW VIEW, CREATE ROUTINE, ALTER ROUTINE ON `ankorcom\_site`.* TO 'ankorcom_user'@'localhost';
