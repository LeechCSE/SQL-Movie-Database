-- Movie
-- Constraint 1) id is a primary key; unique and not NULL
-- Constraint 2) title is not NULL
CREATE TABLE Movie(
       id int NOT NULL,
       title varchar(100) NOT NULL,
       year int,
       rating varchar(10),
       company varchar(50),
       PRIMARY KEY(id)
) ENGINE = InnoDB;
-- Actor
-- Constraint 1) id is a primary key; unique and not NULL
-- Constraint 2) dob is not NULL
-- Constraint 3) CHECK if dob < dod
CREATE TABLE Actor(
       id int NOT NULL,
       last varchar(20),
       first varchar(20),
       sex varchar(6),
       dob date,
       dod date,
       PRIMARY KEY(id),
       CHECK(dob < dod)
) ENGINE = InnoDB;
-- Sales
-- Constraint 1) mid is a primary key; unique and not NULL
-- Constraint 2) Referential integrity; Sales.mid -> Movie.id
-- Constraint 3) CHECK if ticketsSold and totalIncome is a positive int
CREATE TABLE Sales(
       mid int NOT NULL,
       ticketsSold int,
       totalIncome int,
       PRIMARY KEY(mid),
       FOREIGN KEY(mid) references Movie(id),
       CHECK(ticketsSold >= 0 AND totalIncome >= 0)
) ENGINE = InnoDB;
-- Director
-- Constraint 1) id is a primary key; unique and not NULL
-- Constraint 2) dob is not NULL
-- Constarint 3) CHECK if dob < dod
CREATE TABLE Director(
       id int NOT NULL,
       last varchar(20),
       first varchar(20),
       dob date,
       dod date,
       PRIMARY KEY(id),
       CHECK(dob < dod)
) ENGINE = InnoDB;
-- MovieGenre
-- Constraint 1) mid is a primary key; unique and not NULL
-- Constraint 2) genre is a primary key; unique and not NULL
-- Constraint 3) Referential integrity; MovieGenre.mid -> Movie.id
CREATE TABLE MovieGenre(
       mid int NOT NULL,
       genre varchar(20) NOT NULL,
       PRIMARY KEY(mid, genre),
       FOREIGN KEY(mid) references Movie(id)
) ENGINE = InnoDB;
-- MovieDirector
-- Constraint 1) mid is a primary key; unique and not NULL
-- Constraint 2) did is a primary key; unique and not NULL
-- Constraint 3) Referential integrity; MovieDirector.mid -> Movie.id
-- Constraint 4) Referential integrity; MovieDirector.did -> Director.id
CREATE TABLE MovieDirector(
       mid int NOT NULL,
       did int NOT NULL,
       PRIMARY KEY(mid, did),
       FOREIGN KEY(mid) references Movie(id),
       FOREIGN KEY(did) references Director(id)
) ENGINE = InnoDB;
-- MovieActor
-- Constraint 1) mid is a primary key; unique and not NULL
-- Constraint 2) aid is a primary key; unique and not NULL
-- Constraint 3) Referential integrity; MovieActor.mid -> Movie.id
-- Constraint 4) Referential integrity; MovieActor.aid -> Actor.id
CREATE TABLE MovieActor(
       mid int NOT NULL,
       aid int NOT NULL,
       role varchar(50),
       PRIMARY KEY(mid, aid),
       FOREIGN KEY(mid) references Movie(id),
       FOREIGN KEY(aid) references Actor(id)
) ENGINE = InnoDB;
-- MovieRating
-- Constraint 1) mid is a primary key; unique and not NULL
-- Constraint 2) Referential integrity; MovieRating.mid -> Movie.id
-- Constraint 3) CHECK if all ratings are in 0-100
CREATE TABLE MovieRating(
       mid int NOT NULL,
       imdb int,
       rot int,
       PRIMARY KEY(mid),
       FOREIGN KEY(mid) references Movie(id),
       CHECK(imdb >= 0 AND imdb <= 100),
       CHECK(rot >= 0 AND rot <= 100)
) ENGINE = InnoDB;
-- Review
-- Constraint 1) name, time, mid, rating are not NULL
-- Constraint 2) mid is a primary key; unique
-- Constraint 3) Referential integrity; Review.mid -> Movie.id
CREATE TABLE Review(
       name varchar(20) NOT NULL,
       time timestamp NOT NULL,
       mid int NOT NULL,
       rating int NOT NULL,
       comment varchar(500),
       CHECK(rating >= 0 AND rating <= 5),
       FOREIGN KEY(mid) references Movie(id)
) ENGINE = InnoDB;
-- MaxPersonID
-- Constraint 1) id is a primary key; unique and not NULL
CREATE TABLE MaxPersonID(
       id int NOT NULL,
       PRIMARY KEY(id)
) ENGINE = InnoDB;
-- MaxMovieID
-- Constraint 1) id is a primary key; unique and not NULL
CREATE TABLE MaxMovieID(
       id int NOT NULL,
       PRIMARY KEY(id)
) ENGINE = InnoDB;
