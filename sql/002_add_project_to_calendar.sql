-- danzi.tn#6 - 20170718 - add Project as referenceMapping module for field 237 - parent_id / Related To
INSERT INTO vtiger_fieldmodulerel
(fieldid,module,relmodule,status,sequence)
VALUES
 ('237', 'Project', 'Calendar', NULL, NULL);

insert into vtiger_ws_referencetype(fieldtypeid,type) values(34,'Project');
