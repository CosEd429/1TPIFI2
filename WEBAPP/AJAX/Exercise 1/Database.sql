create or replace database citiesAndCountries;

use citiesAndCountries;

create table countries(
    CountryID INT PRIMARY KEY,
    NameOfCountry VARCHAR(50) NOT NULL
);

create table cities(
    CityID INT PRIMARY KEY,
    NameOfCity VARCHAR(50) NOT NULL,
    fk_CountryID INT NOT NULL,
    FOREIGN KEY (fk_CountryID) REFERENCES countries(CountryID)
);

insert into countries (CountryID, NameOfCountry) values
(1, 'United States'),
(2, 'Canada'),
(3, 'Mexico'),
(4, 'United Kingdom'),
(5, 'Germany');

insert into cities (CityID, NameOfCity, fk_CountryID) values
(1, 'New York', 1),
(2, 'Los Angeles', 1),
(3, 'Toronto', 2),
(4, 'Vancouver', 2),
(5, 'Mexico City', 3),
(6, 'Guadalajara', 3),
(7, 'London', 4),
(8, 'Manchester', 4),
(9, 'Berlin', 5),
(10, 'Munich', 5);
