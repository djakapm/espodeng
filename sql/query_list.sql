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