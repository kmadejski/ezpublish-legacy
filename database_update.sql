#Adding two field in table ezcontentobject_tree

alter table ezcontentobject_tree add sort_field int default 1 after path_identification_string;
alter table ezcontentobject_tree add sort_order int(1) default 1 after path_identification_string;


#Adding two field in table eznode_assignment
alter table eznode_assignment add sort_field int default 1 after main;
alter table eznode_assignment add sort_order int(1) default 1 after main;

# Change the length of enum fields
alter table ezenumvalue change enumelement enumelement varchar(255);
alter table ezenumvalue change enumvalue enumvalue varchar(255);

alter table ezenumobjectvalue change enumelement enumelement varchar(255);
alter table ezenumobjectvalue change enumvalue enumvalue varchar(255);


#Adding one field in table ezcontentobject_tree
alter table ezcontentobject_tree add  priority int after sort_order;


#Change the datatype of ezmessage field
alter table ezmessage modify body text;