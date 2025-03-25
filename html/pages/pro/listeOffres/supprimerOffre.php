<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/connect_params.php";
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $idOffre = $_POST['idOffre'];

    try {
        $dbh = new PDO("$driver:host=$server;dbname=$dbname", $dbuser, $dbpass);
        $dbh->beginTransaction();

        $queries = [
            "DELETE FROM pact._possedeactivite WHERE idoffre = :idOffre",
            "DELETE FROM pact._possedevisite WHERE idoffre = :idOffre",
            "DELETE FROM pact._possederestaurant WHERE idoffre = :idOffre",
            "DELETE FROM pact._possedespectacle WHERE idoffre = :idOffre",
            "DELETE FROM pact._possedeparcattractions WHERE idoffre = :idOffre",
            "DELETE FROM pact._guideevisite WHERE idoffre = :idOffre",
            "DELETE FROM pact._representeoffre WHERE idoffre = :idOffre",
            "DELETE FROM pact._facture WHERE idoffre = :idOffre",
            "DELETE FROM pact._historiqueenligne WHERE idoffre = :idOffre",
            "DELETE FROM pact._avis_blacklist WHERE idoffre = :idOffre",
            "DELETE FROM pact._reponseavis WHERE idavis IN (SELECT idavis FROM pact._avis WHERE idoffre = :idOffre)",
            "DELETE FROM pact._avis WHERE idoffre = :idOffre",
            "DELETE FROM pact._annulationoption WHERE idoffre = :idOffre",
            "DELETE FROM pact._activite WHERE idoffre = :idOffre",
            "DELETE FROM pact._visite WHERE idoffre = :idOffre",
            "DELETE FROM pact._restaurant WHERE idoffre = :idOffre",
            "DELETE FROM pact._spectacle WHERE idoffre = :idOffre",
            "DELETE FROM pact._parcattractions WHERE idoffre = :idOffre",
            "DELETE FROM pact._offre WHERE idoffre = :idOffre"
        ];

        foreach ($queries as $query) {
            $stmt = $dbh->prepare($query);
            $stmt->bindParam(':idOffre', $idOffre);
            $stmt->execute();
        }

        // Recreate the materialized view
        $dbh->exec("
            SET SCHEMA 'pact';
            DROP MATERIALIZED VIEW IF EXISTS vue_offres;
            CREATE MATERIALIZED VIEW vue_offres AS
            SELECT DISTINCT _offre.idcompte, _offre.idoffre, _offre.idadresse, _offre.nomoption, _offre.nomforfait,
                   _offre.titre, _offre.description, _offre.descriptiondetaillee, _offre.siteinternet, _offre.heureOuverture, _offre.heureFermeture,_adresse.codepostal, _adresse.ville,
                   COALESCE(_spectacle.nomcategorie, _activite.nomcategorie, _visite.nomcategorie, _parcattractions.nomcategorie, _restaurant.nomcategorie) AS nomcategorie,
                   _adresse.rue, _adresse.numtel,_adresse.coordonneesX,_adresse.coordonneesY,
                   COALESCE(_spectacle.dateEvenement,_visite.dateEvenement) AS dateEvenement,
                   COALESCE(_spectacle.valprix, _activite.valprix, _visite.valprix, _parcattractions.valprix, NULL::numeric) AS valprix,
                   COALESCE(_spectacle.tempsenminutes, _activite.tempsenminutes, _visite.tempsenminutes, NULL::integer) AS tempsenminutes,
                   _spectacle.capacite, COALESCE(_activite.agemin,_parcattractions.agemin) AS ageMin, _activite.prestation,
                   _visite.estguidee, _restaurant.nomgamme, _parcattractions.nbattractions,_offre.estenligne,AVG(note) AS moyNotes, _offre.nbJetons,
                    CASE
                        WHEN _spectacle.idoffre IS NOT NULL THEN 'Spectacle'::text
                        WHEN _restaurant.idoffre IS NOT NULL THEN 'Restaurant'::text
                        WHEN _parcattractions.idoffre IS NOT NULL THEN 'Parc d''attractions'::text
                        WHEN _activite.idoffre IS NOT NULL THEN 'Activite'::text
                        WHEN _visite.idoffre IS NOT NULL THEN 'Visite'::text
                        ELSE 'Inconnu'::text
                    END AS type_offre,
                  (SELECT nomImage 
                    FROM _representeOffre JOIN _image ON _representeOffre.idImage = _image.idImage 
                    WHERE _representeOffre.idOffre = _offre.idoffre 
                    FETCH FIRST 1 ROW ONLY) AS nomimage
            FROM _offre LEFT JOIN _spectacle ON _offre.idoffre = _spectacle.idoffre
                        LEFT JOIN _activite ON _offre.idoffre = _activite.idoffre
                        LEFT JOIN _parcattractions ON _offre.idoffre = _parcattractions.idoffre
                        LEFT JOIN _restaurant ON _offre.idoffre = _restaurant.idoffre
                        LEFT JOIN _visite ON _offre.idoffre = _visite.idoffre
                        LEFT JOIN _adresse ON _offre.idadresse = _adresse.idadresse
                        LEFT JOIN _option ON _offre.nomoption::text = _option.nomoption::text
                        LEFT JOIN _forfait ON _offre.nomforfait::text = _forfait.nomforfait::text
                        LEFT JOIN _representeOffre ON _offre.idoffre = _representeOffre.idoffre
                        LEFT JOIN vue_avis ON vue_avis.idOffre = _offre.idoffre
            GROUP BY _offre.idcompte, _offre.idoffre, _offre.idadresse, _offre.nomoption, _offre.nomforfait,
                   _offre.titre, _offre.description, _offre.descriptiondetaillee, _offre.siteinternet, _offre.heureOuverture, _offre.heureFermeture,_adresse.codepostal, _adresse.ville,
                   _adresse.rue, _adresse.numtel,_adresse.coordonneesX,_adresse.coordonneesY, _spectacle.dateEvenement,_visite.dateEvenement,_spectacle.valprix, _activite.valprix, _visite.valprix, _parcattractions.valprix,
                   _spectacle.tempsenminutes, _activite.tempsenminutes, _visite.tempsenminutes,_spectacle.capacite,_activite.agemin,_parcattractions.agemin, _activite.prestation,
                   _spectacle.nomcategorie, _activite.nomcategorie, _visite.nomcategorie, _parcattractions.nomcategorie, _restaurant.nomcategorie,
                   _visite.estguidee, _restaurant.nomgamme, _parcattractions.nbattractions,_offre.estenligne, type_offre, nomimage;
        ");

        $dbh->commit();
        echo "Offre supprimée";
        header("Location: listeOffres.php");
    } catch (PDOException $e) {
        $dbh->rollBack();
        echo "Erreur !: " . $e->getMessage();
    } finally {
        $dbh = null;
    }
}
?>