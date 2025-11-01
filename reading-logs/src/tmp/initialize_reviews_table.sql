drop table if exists reviews;

create table reviews (
  id integer auto_increment not null primary key,
  title varchar(255),
  author varchar(100),
  status varchar(10),
  score integer,
  summary varchar(1000),
  created_at timestamp not null default current_timestamp
) default character set=utf8mb4
