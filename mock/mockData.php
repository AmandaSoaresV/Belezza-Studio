<?php

$usuarios = [

    [
        "id" => "usr001",
        "nome" => "Amanda Soares",
        "cpf" => "123.456.789-00",
        "email" => "amanda@email.com",
        "hash_senha" => "123456",
        "telefone" => "(44) 99999-1111",
        "tipo_perfil" => "cliente",
        "data_nascimento" => "2005-08-15",
        "created_at" => "2025-01-10",
        "updated_at" => "2025-01-10"
    ],

    [
        "id" => "usr002",
        "nome" => "Julia Martins",
        "cpf" => "987.654.321-00",
        "email" => "julia@email.com",
        "hash_senha" => "123456",
        "telefone" => "(44) 98888-2222",
        "tipo_perfil" => "cliente",
        "data_nascimento" => "1999-02-20",
        "created_at" => "2025-01-15",
        "updated_at" => "2025-01-15"
    ],

    [
        "id" => "usr003",
        "nome" => "Carlos Henrique",
        "cpf" => "456.789.123-99",
        "email" => "carlos@email.com",
        "hash_senha" => "123456",
        "telefone" => "(44) 97777-3333",
        "tipo_perfil" => "admin",
        "data_nascimento" => "1995-11-03",
        "created_at" => "2025-01-20",
        "updated_at" => "2025-01-20"
    ],

    [
        "id" => "usr004",
        "nome" => "Larissa Souza",
        "cpf" => "741.852.963-11",
        "email" => "larissa@email.com",
        "hash_senha" => "123456",
        "telefone" => "(44) 96666-4444",
        "tipo_perfil" => "cliente",
        "data_nascimento" => "2001-04-11",
        "created_at" => "2025-02-01",
        "updated_at" => "2025-02-01"
    ],

    [
        "id" => "usr005",
        "nome" => "Patricia Lima",
        "cpf" => "258.369.147-55",
        "email" => "patricia@email.com",
        "hash_senha" => "123456",
        "telefone" => "(44) 95555-8888",
        "tipo_perfil" => "cliente",
        "data_nascimento" => "1997-09-18",
        "created_at" => "2025-02-10",
        "updated_at" => "2025-02-10"
    ],

    [
        "id" => "usr006",
        "nome" => "Bianca Oliveira",
        "cpf" => "159.357.258-88",
        "email" => "bianca@email.com",
        "hash_senha" => "123456",
        "telefone" => "(44) 94444-9999",
        "tipo_perfil" => "cliente",
        "data_nascimento" => "2000-12-03",
        "created_at" => "2025-02-18",
        "updated_at" => "2025-02-18"
    ]
];


$servicos = [

    [
        "id" => "srv001",
        "nome" => "Corte Feminino",
        "descricao" => "Corte moderno e finalização",
        "foto" => "https://picsum.photos/300/200?1",
        "preco" => "80.00",
        "duracao_em_horas" => 1,
        "created_at" => "2025-01-10",
        "updated_at" => "2025-01-10"
    ],

    [
        "id" => "srv002",
        "nome" => "Escova",
        "descricao" => "Escova lisa com finalização",
        "foto" => "https://picsum.photos/300/200?2",
        "preco" => "50.00",
        "duracao_em_horas" => 1,
        "created_at" => "2025-01-10",
        "updated_at" => "2025-01-10"
    ],

    [
        "id" => "srv003",
        "nome" => "Manicure",
        "descricao" => "Cutilagem e esmaltação",
        "foto" => "https://picsum.photos/300/200?3",
        "preco" => "35.00",
        "duracao_em_horas" => 1,
        "created_at" => "2025-01-10",
        "updated_at" => "2025-01-10"
    ],

    [
        "id" => "srv004",
        "nome" => "Pedicure",
        "descricao" => "Tratamento completo para os pés",
        "foto" => "https://picsum.photos/300/200?4",
        "preco" => "40.00",
        "duracao_em_horas" => 1,
        "created_at" => "2025-01-12",
        "updated_at" => "2025-01-12"
    ],

    [
        "id" => "srv005",
        "nome" => "Coloração",
        "descricao" => "Coloração profissional",
        "foto" => "https://picsum.photos/300/200?5",
        "preco" => "150.00",
        "duracao_em_horas" => 3,
        "created_at" => "2025-01-12",
        "updated_at" => "2025-01-12"
    ],

    [
        "id" => "srv006",
        "nome" => "Luzes",
        "descricao" => "Mechas e iluminação capilar",
        "foto" => "https://picsum.photos/300/200?6",
        "preco" => "220.00",
        "duracao_em_horas" => 4,
        "created_at" => "2025-01-12",
        "updated_at" => "2025-01-12"
    ],

    [
        "id" => "srv007",
        "nome" => "Hidratação",
        "descricao" => "Hidratação profunda",
        "foto" => "https://picsum.photos/300/200?7",
        "preco" => "70.00",
        "duracao_em_horas" => 1,
        "created_at" => "2025-01-13",
        "updated_at" => "2025-01-13"
    ],

    [
        "id" => "srv008",
        "nome" => "Progressiva",
        "descricao" => "Alisamento profissional",
        "foto" => "https://picsum.photos/300/200?8",
        "preco" => "300.00",
        "duracao_em_horas" => 5,
        "created_at" => "2025-01-13",
        "updated_at" => "2025-01-13"
    ]
];


$profissionais = [

    [
        "id" => "pro001",
        "nome" => "Fernanda Alves",
        "especialidade" => "Cabeleireira",
        "foto" => "https://picsum.photos/200/200?11",
        "created_at" => "2025-01-10",
        "updated_at" => "2025-01-10"
    ],

    [
        "id" => "pro002",
        "nome" => "Camila Rocha",
        "especialidade" => "Manicure",
        "foto" => "https://picsum.photos/200/200?12",
        "created_at" => "2025-01-10",
        "updated_at" => "2025-01-10"
    ],

    [
        "id" => "pro003",
        "nome" => "Beatriz Lima",
        "especialidade" => "Colorista",
        "foto" => "https://picsum.photos/200/200?13",
        "created_at" => "2025-01-10",
        "updated_at" => "2025-01-10"
    ],

    [
        "id" => "pro004",
        "nome" => "Juliana Mendes",
        "especialidade" => "Especialista em Progressiva",
        "foto" => "https://picsum.photos/200/200?14",
        "created_at" => "2025-01-11",
        "updated_at" => "2025-01-11"
    ],

    [
        "id" => "pro005",
        "nome" => "Vanessa Costa",
        "especialidade" => "Designer de Unhas",
        "foto" => "https://picsum.photos/200/200?15",
        "created_at" => "2025-01-11",
        "updated_at" => "2025-01-11"
    ]
];

$profissional_servico = [

    ["id" => "ps001", "id_servico" => "srv001", "id_profissional" => "pro001"],
    ["id" => "ps002", "id_servico" => "srv002", "id_profissional" => "pro001"],
    ["id" => "ps003", "id_servico" => "srv005", "id_profissional" => "pro003"],
    ["id" => "ps004", "id_servico" => "srv006", "id_profissional" => "pro003"],
    ["id" => "ps005", "id_servico" => "srv003", "id_profissional" => "pro002"],
    ["id" => "ps006", "id_servico" => "srv004", "id_profissional" => "pro005"],
    ["id" => "ps007", "id_servico" => "srv008", "id_profissional" => "pro004"],
    ["id" => "ps008", "id_servico" => "srv007", "id_profissional" => "pro001"]
];


$agendamentos = [

    [
        "id" => "agd001",
        "id_profissional" => "pro001",
        "id_cliente" => "usr001",
        "id_servico" => "srv001",
        "dia_horario" => "2025-08-12 14:00:00",
        "status" => "confirmado",
        "created_at" => "2025-08-01",
        "updated_at" => "2025-08-01"
    ],

    [
        "id" => "agd002",
        "id_profissional" => "pro002",
        "id_cliente" => "usr002",
        "id_servico" => "srv003",
        "dia_horario" => "2025-08-13 09:30:00",
        "status" => "confirmado",
        "created_at" => "2025-08-01",
        "updated_at" => "2025-08-01"
    ],

    [
        "id" => "agd003",
        "id_profissional" => "pro003",
        "id_cliente" => "usr004",
        "id_servico" => "srv005",
        "dia_horario" => "2025-08-14 15:00:00",
        "status" => "cancelado",
        "created_at" => "2025-08-02",
        "updated_at" => "2025-08-02"
    ],

    [
        "id" => "agd004",
        "id_profissional" => "pro004",
        "id_cliente" => "usr005",
        "id_servico" => "srv008",
        "dia_horario" => "2025-08-15 10:00:00",
        "status" => "confirmado",
        "created_at" => "2025-08-03",
        "updated_at" => "2025-08-03"
    ],

    [
        "id" => "agd005",
        "id_profissional" => "pro001",
        "id_cliente" => "usr006",
        "id_servico" => "srv007",
        "dia_horario" => "2025-08-16 13:00:00",
        "status" => "pendente",
        "created_at" => "2025-08-04",
        "updated_at" => "2025-08-04"
    ],

    [
        "id" => "agd006",
        "id_profissional" => "pro005",
        "id_cliente" => "usr001",
        "id_servico" => "srv004",
        "dia_horario" => "2025-08-17 11:00:00",
        "status" => "confirmado",
        "created_at" => "2025-08-05",
        "updated_at" => "2025-08-05"
    ],

    [
        "id" => "agd007",
        "id_profissional" => "pro003",
        "id_cliente" => "usr002",
        "id_servico" => "srv006",
        "dia_horario" => "2025-08-18 16:30:00",
        "status" => "pendente",
        "created_at" => "2025-08-05",
        "updated_at" => "2025-08-05"
    ],

    [
        "id" => "agd008",
        "id_profissional" => "pro001",
        "id_cliente" => "usr004",
        "id_servico" => "srv002",
        "dia_horario" => "2025-08-19 08:00:00",
        "status" => "confirmado",
        "created_at" => "2025-08-06",
        "updated_at" => "2025-08-06"
    ]
];