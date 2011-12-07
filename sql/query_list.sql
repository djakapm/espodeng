#Search Location Query

select orl.id,ord.name as district_name,orc.name as city_name,ors.name as state_name
from ongkir_ref_location orl
left join ongkir_ref_district ord on ord.id = orl.district_id
inner join ongkir_ref_city orc on orc.id = orl.city_id
inner join ongkir_ref_state ors on ors.id = orl.state_id
where ord.name like '%bandung%'
or orc.name like '%bandung%'
or ors.name like '%bandung%'
order by ord.name, orl.id
limit 10;

#Load Location Query 

select orl.id,case when ord.name is null then orc.name else ord.name end as name
from ongkir_ref_location orl
left join ongkir_ref_district ord on ord.id = orl.district_id
inner join ongkir_ref_city orc on orc.id = orl.city_id
where orl.id=5735;

#Get Origin Location for Frontend

select orl.id,orl.city_name from ongkir_ref_location orl where orl.id in 
(select id from ongkir_ref_origin_location)
and orl.city_name like '%jakarta%'
order by orl.city_name,orl.id;


#ongkir_ref_origin_location

CREATE TABLE `ongkir_ref_origin_location` (
 `id` int(11) NOT NULL,
 `name` varchar(200) NOT NULL,
 PRIMARY KEY (`name`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='Hold supported origin location'

