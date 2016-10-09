create table Item (
    upc integer,
    title varchar(30),
    type varchar(30),
    category varchar(30),
    company varchar(30),
    year integer,
    price decimal(7,2),
    stock integer,
    imgurl varchar(50),
    primary key(upc) );

create table LeadSinger (
    upc integer ,
    name varchar(30),
    primary key (upc,name),
    foreign key (upc) references Item (upc) ON DELETE CASCADE ON UPDATE CASCADE);
    
create table HasSong (
    upc integer,
    title varchar(30),
    primary key (upc, title),
    foreign key (upc) references Item (upc) ON DELETE CASCADE ON UPDATE CASCADE);
    
create table Customer(
    cid varchar(40),
    password varchar(30),
    name varchar(30),
    address varchar(30),
    phone varchar(20),
    primary key (cid) );
                                                                              
create table Orders (
    receiptId integer,
    date date,
    cid varchar(40),
    cardnumber varchar(30),
    expiryDate date,
    expectedDate date,
    deliveredDate date,
    primary key (receiptId),
    foreign key (cid) references Customer(cid) );

create table PurchasedItem(
    receiptId integer,
    upc integer,
    quantity integer,
    primary key (receiptId, upc),
    foreign key (receiptId) references Orders (receiptID),
    foreign key (upc) references Item (upc) );
    
create table Returned(
    retid integer,
    date date,
    receiptId integer,
    primary key (retid),
    foreign key (receiptId) references Orders (receiptID));
    
create table ReturnItem(
    retid integer,
    upc integer,
    quantity integer,
    primary key (retid, upc),
    foreign key (retid) references Returned (retid),
    foreign key (upc) references Item (upc) );

create table Basket(
	cid varchar(40), 
	upc int(11), 
	qty int(11), 
	primary key (cid, upc), 
	foreign key (cid) references Customer (cid), 
	foreign key (upc) references Item (upc));