create table soyshop_item_review(
	id integer primary key AUTOINCREMENT,
	item_id integer not null,
	user_id integer,
	nickname varchar,
	title varchar,
	content varchar,
	image varchar,
	movie varchar,
	evaluation integer,
	approval integer,
	vote integer,
	attributes varchar,
	is_approved integer not null,
	create_date integer not null,
	update_date integer
);