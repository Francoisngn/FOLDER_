SELECT DISTINCT C.NOM, c.PRENOM

FROM chercheur c JOIN AFFa JOIN projet p ON c.NC = a.NC AND a.NP = p.NP

where budget >30000 

GROUP BY a.ANNEE, c.NOM, c.PRENOM Having Count (a.NP) >2;


11.  Lister les noms et prenoms des chercheurs qui ont particpé a plus de 2 projets durant 1 annnee et dont le budget 
est superieur a 30k