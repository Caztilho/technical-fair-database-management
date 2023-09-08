# Professores

É necessário estar logado para utilizar a rota "professores"

- ### POST
    - http://localhost/professores
        - `{
            "registro": 1,
            "nome": "hélio",
            "nascimento": "25/12/2000"
          }`

        - `{
            "registro": 2,
            "nome": "alberson",
            "nascimento": "19/02/1900"
          }`

        - `{
            "registro": 3,
            "nome": "bruno",
            "nascimento": "19/06/2023"
          }`

        - `{
        "registro": 4,
        "nome": "Wagner",
        "nascimento": "19/06/2023"
          }`

    - http://localhost/login
        - `{
            "registro": 1,
            "nome": "hélio"
          }`
        
    (Adicionar token do login no cabeçalho da requisição)

- ### GET
    - http://localhost/professores (retorna todos)
    - http://localhost/professores/1 (retorna o com ID específico)
    
- ### PUT
    - http://localhost/professores/1 (ID específico)
    
        - `{
            "registro": 1,
            "nome": "hélio esperidião",
            "nascimento": "25/12/2049"
          }`

- ### DELETE
    - http://localhost/professores/4 (ID específico)


# Curso

É necessário estar logado para utilizar a rota "cursos"

- ### POST
    - http://localhost/cursos

        - `{
            "idCurso": 1,
            "nomeCurso": "Info"
          }`

        - `{
            "idCurso": 2,
            "nomeCurso": "Eletrônica"
          }`   

        - `{
            "idCurso": 3,
            "nomeCurso": "Publicidade"
          }`     

- ### GET
    - http://localhost/cursos (retorna todos)
    - http://localhost/cursos/1 (retorna o com ID específico)
    
- ### PUT
    - http://localhost/cursos/1 (ID específico)

        - `{
            "nomeCurso": "Informática"
          }`

- ### DELETE
    - http://localhost/cursos/3 (ID específico)


# Trabalho

- ### POST
    - http://localhost/trabalhos

        - `{
            "idTrabalho": 1,
            "nomeTrabalho": "Inteligência Artificial com Python",
            "resumo": "...",
            "curso_idCurso": 1
          }`

        - `{
            "idTrabalho": 2,
            "nomeTrabalho": "Óculos com visor para surdos",
            "resumo": "Resumo do Trabalho 2",
            "curso_idCurso": 2
          }`  

        - `{
            "idTrabalho": 3,
            "nomeTrabalho": "Site moderno",
            "resumo": "...",
            "curso_idCurso": 1
          }`   

- ### GET
    - http://localhost/trabalhos (retorna todos)
    - http://localhost/trabalhos/1 (retorna o com ID específico)
    
- ### PUT
    - http://localhost/trabalhos/2 (ID específico)

        - `{
            "nomeTrabalho": "Óculos com tradução em tempo real",
            "resumo": "..."
           }`

- ### DELETE
    - http://localhost/trabalhos/3 (ID específico)


# Alunos Grupo

- ### POST
    - http://localhost/alunos

        - `{
            "trabalho_idTrabalho": 1,
            "matriculaAluno": 50220155,
            "nomeAluno": "João Araujo",
            "turmaAluno": "2°H"
           }`

        - `{
            "trabalho_idTrabalho": 2,
            "matriculaAluno": 50220149,
            "nomeAluno": "João Castilho",
            "turmaAluno": "2°H"
           }`  

        - `{
            "trabalho_idTrabalho": 1,
            "matriculaAluno": 50220169,
            "nomeAluno": "Lucca Monteiro",
            "turmaAluno": "3°Z"
           }` 

- ### GET
    - http://localhost/alunos (retorna todos)
    - http://localhost/alunos/50220169 (retorna pela matrícula)
    
- ### PUT
    - http://localhost/alunos/50220169 (matrícula)

        - `{
            "trabalho_idTrabalho": 1,
            "matriculaAluno": 50220169,
            "nomeAluno": "Lucca Monteiro",
            "turmaAluno": "2°H"
           }` 

- ### DELETE
    - http://localhost/alunos/50220169 (matrícula)


# Avaliações

- ### POST
    - http://localhost/avaliacoes

        - `{
            "idAvaliacao": 1,
            "professor_registro": 1,
            "notaGeral": 8,
            "obs": "faltou um pouquinho pra 10",
            "trabalho_idTrabalho": 1
           }`

        - `{
            "idAvaliacao": 2,
            "professor_registro": 2,
            "notaGeral": 5,
            "obs": "trabalho mediano",
            "trabalho_idTrabalho": 2
           }`

        (sem registro para ser anônima)
        - `{
            "idAvaliacao": 3,
            "professor_registro":null,
            "notaGeral": 7,
            "obs": "bem bom",
            "trabalho_idTrabalho": 1
           }`   

- ### GET
    - http://localhost/avaliacoes (retorna todos)
    - http://localhost/avaliacoes/1 (retorna o com ID específico)
    
- ### PUT
    - http://localhost/avaliacoes/1 (ID específico)

        - `{
            "idAvaliacao": 1,
            "professor_registro": 1,
            "notaGeral": 10,
            "obs": "trabalho perfeito",
            "trabalho_idTrabalho": 1
           }`

- ### DELETE
    - http://localhost/avaliacoes/3 (ID específico)


# Rotas GET especiais

- ### Avaliações
    Retorna as últimas 10 avaliações com comentários
    - http://localhost/avaliacoes/comentarios

- ### Trabalhos
    - Retorna os trabalhos em ordem alfabética
        - http://localhost/trabalhos/alfabetico

    - Retorna todos os trabalhos de um curso específico
        - http://localhost/trabalhos/curso/1 

    - Retorna os trabalhos por ordem de nota
        - http://localhost/trabalhos/nota 

    - Retorna o melhor trabalho
        - http://localhost/trabalhos/melhor

    - Retorna o melhor trabalho por curso
        - http://localhost/trabalhos/melhor/1

