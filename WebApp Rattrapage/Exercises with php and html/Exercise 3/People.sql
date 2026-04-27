drop database ppl;
create database ppl;
use ppl;

create table Persons(
    personId int primary key auto_increment,
    personName varchar(50) unique,
    personAge int
);

insert into Persons(personName,personAge) VALUES ("John",25),("Angelina",17),("Mark",21),("OldMan",50),("BabyDear",3),("AnotherPerson",22);