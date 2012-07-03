--------------------
-- Main database: --
--------------------

-- CREATE USER egfzf PASSWORD 'pass';
-- CREATE DATABASE egfzf ENCODING 'UTF8' OWNER egfzf;
-- GRANT ALL ON DATABASE egfzf TO egfzf WITH GRANT OPTION;

------------------------------
-- Test database (if used): --
------------------------------

-- CREATE USER egfzf_test PASSWORD 'pass_test';
-- CREATE DATABASE egfzf_test ENCODING 'UTF8' OWNER egfzf_test;
-- GRANT ALL ON DATABASE egfzf_test TO egfzf_test WITH GRANT OPTION;

BEGIN;

--
-- Table structure for table "bouquets" / Bouquets of flowers
--
CREATE TABLE "bouquets" (
  "id" serial,
  "customer_name" varchar(32),
  "customer_phone" varchar(32) DEFAULT NULL,
  "date" date,
  "price" float DEFAULT NULL,
  "count_of_flowers" numeric(3) DEFAULT 1,
  "is_complete" boolean DEFAULT false,
  "id_wrapper" integer,
  PRIMARY KEY ("id")
);
COMMENT ON TABLE "bouquets" IS 'Bouquets of flowers';

--
-- Table structure for table "flowers" / Flowers
--
CREATE TABLE "flowers" (
  "id" serial,
  "title" varchar(32),
  PRIMARY KEY ("id")
);
COMMENT ON TABLE "flowers" IS 'Flowers';

--
-- Table structure for table "bouquets_flowers" / Link bouquets of flowers and flowers
--
CREATE TABLE "bouquets_flowers" (
  "id" serial,
  "id_bouquet" integer,
  "id_flower" integer,
  PRIMARY KEY ("id"),
  UNIQUE("id_bouquet", "id_flower")
);
COMMENT ON TABLE "bouquets_flowers" IS 'Link bouquets of flowers and flowers';

--
-- Table structure for table "wrappers" / Wrappers for the bouquet
--
CREATE TABLE "wrappers" (
  "id" serial,
  "title" varchar(128),
  PRIMARY KEY ("id")
);
COMMENT ON TABLE "wrappers" IS 'Wrappers for the bouquet';

--
-- Constraints
--
ALTER TABLE "bouquets"
  ADD CONSTRAINT "bouquets_id_wrapper_fkey"
  FOREIGN KEY ("id_wrapper")
  REFERENCES "wrappers" ("id")
  ON UPDATE NO ACTION ON DELETE NO ACTION;

ALTER TABLE "bouquets_flowers"
  ADD CONSTRAINT "bouquets_flowers_id_bouquet_fkey"
  FOREIGN KEY ("id_bouquet")
  REFERENCES "bouquets" ("id")
  ON UPDATE CASCADE ON DELETE CASCADE;

ALTER TABLE "bouquets_flowers"
  ADD CONSTRAINT "bouquets_flowers_id_flower_fkey"
  FOREIGN KEY ("id_flower")
  REFERENCES "flowers" ("id")
  ON UPDATE CASCADE ON DELETE CASCADE;

ROLLBACK;
--COMMIT;

