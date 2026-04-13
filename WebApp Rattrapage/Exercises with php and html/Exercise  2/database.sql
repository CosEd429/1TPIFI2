CREATE  OR REPLACE DATABASE people;
USE people;

CREATE TABLE persons(
    personName VARCHAR(100) PRIMARY KEY,
    age INT
);

INSERT INTO persons(personName , age) VALUES("John" , 29);
INSERT INTO persons(personName , age) VALUES("Peeta" , 18);
INSERT INTO persons(personName , age) VALUES("Angelina" , 25);

