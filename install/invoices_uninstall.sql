DROP TABLE IF EXISTS `email_templates`;
DROP TABLE IF EXISTS `invoices`;
DELETE FROM `fuel_permissions` WHERE `name` LIKE 'invoices/%';