create table if not exists l_sale_basket
(
    ID int not null auto_increment,
    FUSER_ID int not null,
    PRODUCT_ID int not null,
    NAME varchar(255) null,
    DETAIL_PAGE_URL varchar(255) null,
    PREVIEW_PICTURE int null,
    DETAIL_PICTURE int null,
    IBLOCK_ID int not null,
    IBLOCK_SECTION_ID int not null,
    DATE_INSERT datetime not null,
    DATE_UPDATE datetime not null,
    QUANTITY int not null,
    primary key (ID),
    index IXS_BASKET_USER_ID(FUSER_ID),
    index IXS_BASKET_PRODUCT_ID(PRODUCT_ID),
    index IXS_BASKET_DATE_INSERT(DATE_INSERT)
);

create table if not exists l_sale_basket_property
(
    ID int not null auto_increment,
    BASKET_ID int not null,
    NAME varchar(255) null,
    VALUE varchar(255) null,
    primary key (ID)
);

create table if not exists l_sale_fuser
(
    ID int not null auto_increment,
    DATE_INSERT datetime not null,
    DATE_UPDATE datetime not null,
    USER_ID int not null,
    CODE varchar(255) null,
    primary key (ID)
);