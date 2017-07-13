SELECT 
CONCAT(SUBSTRING(its4you_multicompany4you_cn.prefix,1,2), 
'-',
SUBSTRING(its4you_multicompany4you_cn.prefix,3,2), 
'_'),
LPAD(its4you_multicompany4you_cn.start_id,5,'0'),
LPAD(its4you_multicompany4you_cn.cur_id,5,'0') 
FROM vtiger.its4you_multicompany4you_cn
WHERE its4you_multicompany4you_cn.start_id='1';


UPDATE
its4you_multicompany4you_cn
SET 
its4you_multicompany4you_cn.prefix =
CONCAT(SUBSTRING(its4you_multicompany4you_cn.prefix,1,2), 
'-',
SUBSTRING(its4you_multicompany4you_cn.prefix,3,2), 
'_'),
its4you_multicompany4you_cn.start_id = LPAD(its4you_multicompany4you_cn.start_id,5,'0'),
its4you_multicompany4you_cn.cur_id = LPAD(its4you_multicompany4you_cn.cur_id,5,'0') 
WHERE its4you_multicompany4you_cn.start_id='1';
