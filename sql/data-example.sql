--
-- Example data
--

BEGIN;

TRUNCATE TABLE "flowers" CASCADE;
TRUNCATE TABLE "wrappers" CASCADE;
TRUNCATE TABLE "bouquets" CASCADE;
TRUNCATE TABLE "bouquets_flowers" CASCADE;

INSERT INTO "flowers" ("id", "title") VALUES
('1', 'Rose'),
('2', 'Tulip'),
('3', 'Narcissus'),
('4', 'Chamomile'),
('5', 'Dandelion');

INSERT INTO "wrappers" ("id", "title") VALUES
('1', 'Thin blue'),
('2', 'Thin red'),
('3', 'Thick yellow'),
('4', 'Wrinkled green');

INSERT INTO "bouquets" ("id", "customer_name", "customer_phone", "date", "price", "count_of_flowers", "is_complete",
    "id_wrapper") VALUES
('1', E'Bob',   null,           '2012-02-16', null,     '5',  true,  '2'),
('2', E'Mike',  E'123-456-789', '2012-02-18', '100.50', '10', false, '4'),
('3', E'Alice', E'123-456-789', '2012-02-20', '400.0',  '20', false, '1');

INSERT INTO "bouquets_flowers" ("id", "id_bouquet", "id_flower") VALUES
('1', '1', '3'),
('2', '1', '2'),
('3', '2', '3'),
('4', '2', '4'),
('5', '2', '5'),
('6', '3', '1');

--ROLLBACK;
COMMIT;

