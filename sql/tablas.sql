create table usuarios(
    id int auto_increment primary key,
    nombre varchar(45),
    apellidos varchar(45),
    email varchar(45) unique not null,
    is_admin enum('SI', 'NO') default 'NO',
    sueldo float(6,2)
);
insert into usuarios(nombre, apellidos, email, is_admin, sueldo)
values (
        'Daniel',
        'Calatrava Gonzalez',
        'dannycalgon@gmail.com',
        1,
        '1500'
    );
insert into usuarios(nombre, apellidos, email, is_admin, sueldo)
values (
        'Camilo',
        'Fernandez Ramos',
        'camilo@gmail.com',
        1,
        '1200.50'
    );
insert into usuarios(nombre, apellidos, email, is_admin, sueldo)
values (
        'Carlos',
        'Rodriguez Sanchez',
        'carlos@gmail.com',
        2,
        '1000'
    );