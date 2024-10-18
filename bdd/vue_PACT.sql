--
-- SCHEMA
--

SET SCHEMA 'pact';

-- VUE COMPTE PRO

create or replace view CompteProPrive as
select idCompte, login, mdp, mail, numTel, denominationSociale, raisonSocialePro, banqueRib
from _compte NATURAL JOIN _comptePro NATURAL JOIN CompteProPrive NATURAL JOIN _adresse;

/*-- VUE COMMENT

create or replace view comment_view as
select iddoc, content, create_date
from _comment natural join _document;

-- VUE POST

create or replace view post_view as
select iddoc, content, create_date, author
from _post natural join _document;*/
