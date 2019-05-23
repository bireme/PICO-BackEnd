var globalLanguage = 0;

function getSearchDetailsInfo() {
    return ['Please update the results...',
        'Por favor atualize os resultados...',
        'Por favor actualiza los resultados...',
        "Veuillez mettre à jour les résultats..."
    ];
}

function getPICOPlaceHolder(PICOnum) {
    var PHarr = ['Dengue, Paraguay, Obese, Adults, Children, Neoplasm',
        'Igg, lumbal puncture, nerve graft, aspirine, X-ray ',
        'Igm, blood test, nerve tranfer, codeine, TAC ',
        'Sensitivity, Mortality, Recovery, Functional, Time, Movility'
    ];
    if (PICOnum < 5) {
        return MessageCode(134) + PHarr[PICOnum - 1];
    }
}

function MessageCode(Code, Lang) {
    var Message;
    switch (Code) {
        case 111:
            Message = ['POPULATION',
                'POPULAÇÃO',
                'POBLACIÓN',
                'POPULATION'
            ];
            break;
        case 112:
            Message = ['INTERVENTION',
                'INTERVENÇÃO',
                'INTERVENCIÓN',
                'INTERVENTION'
            ];
            break;
        case 113:
            Message = ['COMPARISON',
                'COMPARAÇÃO',
                'COMPARACIÓN',
                'COMPARAISON'
            ];
            break;
        case 114:
            Message = ['OUTCOMES',
                'OUTCOMES',
                'OUTCOMES',
                'OUTCOMES'
            ];
            break;
        case 115:
            Message = ['TYPE OF STUDY',
                'TIPO DE ESTUDO',
                'TIPO DE ESTUDIO',
                "TYPE D'ETUDE"
            ];
            break;
            break;
        case 116:
            Message = ['GLOBAL QUERY',
                'CONSULTA GLOBAL',
                'CONSULTA GLOBAL',
                "REQUÊTE GLOBALE"
            ];
            break;
        case 121:
            Message = ['All Fields',
                'Todos os campos',
                'Todos los campos',
                "Tous les champs"
            ];
            break;
        case 122:
            Message = ['Title, Abstract, DeCS/MeSH terms',
                'Título, resumo, termos DeCS / MeSH',
                'Título, resumen, términos DeCS / MeSH',
                "Titre, Résumé, Termes DeCS / MeSH"
            ];
            break;
        case 123:
            Message = ['Title',
                'Título',
                'Tìtulo',
                "Titre"
            ];
            break;
        case 124:
            Message = ['DeCs/MeSH terms',
                'Termos DeCS/MeSH',
                'Términos DeCS/MeSH',
                "Termes DeCS/MeSH"
            ];
            break;
        case 131:
            Message = ['Expand DeCS/MeSH terms',
                'Expandir os termos DeCS / MeSH',
                'Expandir los términos de DeCS / MeSH',
                "Développer les termes DeCS / MeSH"
            ];
            break;
        case 132:
            Message = ['Results',
                'Resultados',
                'Resultados',
                "Résultats"
            ];
            break;
        case 133:
            Message = ['Search details',
                'Detalhes da pesquisa',
                'Detalles de búsqueda',
                "Détails de la recherche"
            ];
            break;
        case 134:
            Message = ['Keywords as: ',
                'Palavras-chave como: ',
                'Palabras clave como: ',
                "Mots-clés comme: "
            ];
            break;
        case 135:
            Message = ['Select the lenguages of the expanded terms that will be imported',
                'Selecione os idiomas dos termos expandidos que serão importados',
                'Seleccione los idiomas de los términos expandidos que serán importados',
                "Sélectionnez les langues des termes développés qui seront importés"
            ];
            break;
        case 136:
            Message = ['Search results',
                'Resultados da pesquisa',
                'Resultados de búsqueda',
                "Résultats de la recherche"
            ];
            break;
        case 137:
            Message = ['PICOS Search - All rights reserved',
                'PICOS Search - Todos os direitos reservados',
                'PICOS Search - Todos los derechos reservados',
                "PICOS Search - Tous les droits sont réservés"
            ];
            break;
        case 138:
            Message = ['Update results',
                'Atualizar resultados',
                'Actualizar resultados',
                "Mettre à jour les résultats"
            ];
            break;
        case 139:
            Message = ['Click to see results',
                'Clique para ver os resultados',
                'Haga clic para ver los resultados',
                "Cliquez pour voir les résultats"
            ];
            break;
        case 161:
            Message = ['Press ⟳ to update results',
                'Pressione ⟳ para atualizar os resultados',
                'Presiona ⟳ para actualizar los resultados',
                "Appuyez sur ⟳ pour mettre à jour les résultats"
            ];
            break;


        case 141:
            Message = ['Keywords describing the characteristics or conditions present in the population of interest and the ones to exclude',
                'Palavras-chave que descrevam as características ou condições presentes na população de interesse e as que excluem',
                'Palabras clave que describen las características o condiciones presentes en la población de interés y las que se excluyen',
                "Mots-clés décrivant les caractéristiques ou les conditions présentes dans la population d'intérêt et celles à exclure"
            ];
            break;

        case 142:
            Message = ['Keywords describing the interventions, procedures, diagnostic tests, expositions or treatments to assess. If you only introduce intervensions, the search can contain ANY of these keywords',
                'Palavras-chave descrevendo as intervenções, procedimentos, testes de diagnóstico, exposições ou tratamentos para avaliar. Se você introduzir apenas intervenções, a pesquisa poderá conter QUALQUER dessas palavras-chave',
                'Palabras clave que describen las intervenciones, procedimientos, pruebas de diagnóstico, exposiciones o tratamientos a evaluar. Si solo introduce intervenciones, la búsqueda puede contener CUALQUIERA de estas palabras clave',
                "Mots-clés décrivant les interventions, procédures, tests de diagnostic, expositions ou traitements à évaluer. Si vous n'entrez que des interventions, la recherche peut contenir N'IMPORTE QUEL de ces mots-clés"
            ];
            break;

        case 143:
            Message = ['Keywords describing the interventions, procedures, diagnostic tests, expositions or treatments to be compared with the previous ones. If you include intervensions and comparisons, the search must contain BOTH of these queries',
                'Palavras-chave descrevendo as intervenções, procedimentos, testes diagnósticos, exposições ou tratamentos a serem comparados com os anteriores. Se você incluir intervenções e comparações, a pesquisa deverá conter AMBAS dessas consultas',
                'Palabras clave que describen las intervenciones, procedimientos, pruebas diagnósticas, exposiciones o tratamientos a comparar con los anteriores. Si incluye intervenciones y comparaciones, la búsqueda debe contener AMBAS de estas consultas',
                "Mots-clés décrivant les interventions, procédures, tests de diagnostic, expositions ou traitements à comparer avec les précédents. Si vous incluez des intervalles et des comparaisons, la recherche doit contenir à la fois ces deux requêtes"
            ];
            break;

        case 144:
            Message = ['Keywords describing the measurements, conditions or characteristics which will determine the differences between the interventions, procedures, diagnostic tests, expositions or treatments previously mentioned',
                'Palavras-chave descrevendo as medidas, condições ou características que determinarão as diferenças entre as intervenções, procedimentos, testes diagnósticos, exposições ou tratamentos anteriormente mencionados',
                'Palabras clave que describen las mediciones, condiciones o características que determinarán las diferencias entre las intervenciones, procedimientos, pruebas de diagnóstico, exposiciones o tratamientos mencionados anteriormente',
                "Mots clés décrivant les mesures, conditions ou caractéristiques permettant de déterminer les différences entre les interventions, procédures, tests de diagnostic, expositions ou traitements précédemment mentionnés"
            ];
            break;
        case 145:
            Message = ['Type of studies of interest',
                'Tipo de estudos de interesse',
                'Tipo de estudios de interés',
                "Type d'études d'intérêt"
            ];
            break;
        case 146:
            Message = ["These are some of the most common reasons to get zero results: \n- There are to many ANDs: How probable is that an article contains all that keywords? \n- You searched only in titles: There's a big chance that your query is in other fields \n- Some of your keywords are mispelled, check them one by one \n- The syntax of your equation is wrong",
                "Estas são algumas das razões mais comuns para obter resultados zero: \n - Existem para muitos ANDs: Qual é a probabilidade de um artigo conter todas as palavras-chave? \n- Você pesquisou somente em títulos: Há uma grande chance de que sua consulta esteja em outros campos \n- Algumas de suas palavras-chave estão com erros ortográficos, verifique-as uma a uma \n- A sintaxe de sua equação está errada",
                "Estas son algunas de las razones más comunes para obtener resultados nulos: \n- Hay muchos AND: ¿Qué tan probable es que un artículo contenga todas esas palabras clave? \n: buscó solo en títulos: existe una gran posibilidad de que su consulta esté en otros campos \n: algunas de sus palabras clave están mal escritas, verifíquelas una por una. \n: la sintaxis de su ecuación es incorrecta",
                "Voici quelques-unes des raisons les plus courantes pour obtenir des résultats nuls: \n - Il y a beaucoup d'AND: Quelle est la probabilité qu'un article contienne tous ces mots-clés? \n- Vous avez cherché uniquement dans les titres: il y a de grandes chances que votre requête soit dans d'autres champs \n- Certains de vos mots clés sont mal orthographiés, vérifiez-les un à un"
            ];
            break;

        case 102:
            Message = ['WARNING',
                'AVISO',
                'ADVERTENCIA',
                'ATTENTION'
            ];
            break;
        case 103:
            Message = ['SUCESSFUL OPERATION',
                'OPERAÇÃO DE SUCESSO',
                'OPERACION EXITOSA',
                'OPÉRATION RÉUSSIE'
            ];
            break;
        case 104:
            Message = ['INFORMATION',
                'INFORMAÇAO',
                'INFORMACIÓN',
                'INFORMATION'
            ];
            break;
        case 105:
            Message = ['SUCESSFUL OPERATION',
                'OPERAÇÃO DE SUCESSO',
                'OPERACION EXITOSA',
                'OPÉRATION RÉUSSIE'
            ];
            break;
        case 106:
            Message = ['CONFIGURATION',
                'CONFIGURAÇÃO',
                'CONFIGURACIÓN',
                'CONFIGURATION'
            ];
            break;
        case 1:
            Message = ['Error while trying to connect to the server',
                'Erro ao tentar se conectar ao servidor',
                'Error al intentar conectarse al servidor',
                'Erreur lors de la tentative de connexion au serveur'
            ];
            break;
        case 2:
            Message = ['Unknown error in the server while request was being processed',
                'Erro desconhecido no servidor enquanto estava-se processando a informaçõao',
                'Error desconocido en el servidor mientras se estaba procesando la información',
                'Erreur de serveur inconnue lors du traitement des informations'
            ];
            break;
        case 3:
            Message = ['Please allow popups for this website',
                'Por favor, permita pop-ups para este site',
                'Por favor permita popups para este sitio web',
                "S'il vous plaît autoriser les popups pour ce site"
            ];
            break;



        case 21:
            Message = ['You must calculate the results first. Press the Refresh button',
                'Primeiro você deve calcular os resultados. Pressione o botão Atualizar',
                'Primero debes calcular los resultados. Presiona el botón Refresh',
                'Vous devez d’abord calculer les résultats. Appuyez sur le bouton Actualiser'
            ];
            break;
        case 22:
            Message = ['The query was changed. You must recalculate results first',
                'A consulta foi alterada. Você deve recalcular os resultados primeiro',
                'La consulta fue cambiada. Primero debes recalcular los resultados',
                "La requête a été changée. Vous devez d'abord recalculer les résultats"
            ];
            break;
        case 23:
            Message = ['Please update the results...',
                'Por favor atualize os resultados...',
                'Por favor actualiza los resultados...',
                "Veuillez mettre à jour les résultats..."
            ];
            break;
        case 24:
            Message = ['The query is empty, please introduce the respective keywords',
                'A consulta está vazia, insira as respectivas palavras-chave',
                'La consulta está vacía, por favor introduzca las respectivas palabras clave',
                "La requête est vide, veuillez saisir les mots-clés correspondants"
            ];
            break;
        case 25:
            Message = ['All the queries are empty, please introduce keywords in any of them',
                'Todas as consultas estão vazias, por favor, introduza palavras-chave em qualquer uma delas',
                'Todas las consultas están vacías, por favor introduzca palabras clave en cualquiera de ellas.',
                "Toutes les requêtes sont vides, veuillez introduire des mots-clés dans l'un d'eux."
            ];
            break;
        case 31:
            Message = ['Number of results updated for:\n',
                'Número de resultados atualizados para:\n',
                'Número de resultados actualizados para:\n',
                'Nombre de résultats mis à jour pour:\n'
            ];
            break;
        case 201:
            Message = ['English',
                'Inglês',
                'Inglés',
                'Anglais'
            ];
            break;
        case 202:
            Message = ['Portuguese',
                'Português',
                'Portugués',
                'Portugais'
            ];
            break;
        case 203:
            Message = ['Spanish',
                'Espanhõl',
                'Español',
                'Espagnol'
            ];
            break;
        case 204:
            Message = ['French',
                'Francês',
                'Francés',
                'Français'
            ];
            break;
        case 221:
            Message = ['Loading...',
                'Cargando...',
                'Cargando...',
                'Chargement...'
            ];
            break;
        case 222:
            Message = ['Cancel',
                'Cancelar',
                'Cancelar',
                'Annuler '
            ];
            break;
        case 231:
            Message = ['Select DeCS/MeSH Descriptor',
                'Selecione o descritor DeCS/MeSH',
                'Seleccione el descriptor DeCS/MeSH',
                'Sélectionner le descripteur DeCS/MeSH '
            ];
            break;
        case 232:
            Message = ['Continue',
                'Continuar',
                'Continuar',
                'Continuer '
            ];
            break;
        case 233:
            Message = ['Select DeCS/MeSH Synonym',
                'Selecione DeCS / MeSH Sinônimo',
                'Seleccione DeCS / MeSH Sinónimo',
                'Sélectionnez le synonyme DeCS / MeSH '
            ];
            break;
        case 234:
            Message = ['Free keywords',
                'Palavras-chave livres',
                'Palabras clave libres',
                'Mots-clés libres '
            ];
            break;
        case 235:
            Message = ['Improve search',
                'Melhore a pesquisa',
                'Mejorar la búsqueda',
                'Améliorer la recherche'
            ];
            break;
        case 236:
            Message = ["Ex: tw:(chicungunya) AND (instance:'regional')",
                "Ex: tw:(chicungunya) AND (instance:'regional')",
                "Ej: tw:(chicungunya) AND (instance:'regional')",
                "Ex: tw:(chicungunya) AND (instance:'regional')"
            ];
            break;
        default:
            Message =
                    ['Unknown Error',
                        'Erro desconhecido',
                        'Error desconocido',
                        'Erreur inconnue'
                    ];
            break;
    }
    if (Lang !== undefined) {
        var Lan = Lang;
    } else {
        var Lan = getLanguage();
    }
    return Message[Lan];
}