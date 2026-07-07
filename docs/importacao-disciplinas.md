# Importacao de disciplinas automaticas

O arquivo `importar_disciplinas.php` importa equivalencias automaticas a partir
de arquivos JSON armazenados em `storage/app/TAA`.

Cada arquivo JSON representa as equivalencias de um curso e habilitacao. O
primeiro objeto identifica o curso. Os objetos seguintes representam os
conjuntos de equivalencia que serao cadastrados como aproveitamentos automaticos.

## Como usar

1. Coloque os arquivos `.json` em `storage/app/TAA`.
2. Execute o script dentro do contexto da aplicacao Laravel.
```bash
php artisan tinker --execute='eval(file_get_contents("importar_disciplinas.php"));'
```
3. Ao final, o script exibe um resumo com arquivos processados,
   aproveitamentos criados, aproveitamentos ja existentes e registros ignorados.

O script procura todos os arquivos com extensao `.json` nessa pasta:

```php
storage_path('app/TAA/*.json')
```

Ao reimportar um arquivo, o script tenta manter a operacao idempotente. Se ja
existir um aproveitamento automatico no mesmo curso/habilitacao, com a mesma
disciplina requerida e o mesmo conjunto de disciplinas cursadas, ele atualiza os
dados disponiveis em vez de criar uma duplicata.

## Formato geral do JSON

O JSON deve ser um array. O primeiro item deve conter o campo `curso` no formato:

```json
{
  "curso": "Nome do Curso (codcur/codhab)"
}
```

Exemplo:

```json
{
  "curso": "Engenharia de Producao (18084/0)"
}
```

Os demais itens representam equivalencias:

```json
{
  "periodo": " ",
  "codigo_disciplina_requerida": "SMA0301",
  "nome_disciplina_requerida": "Calculo I",
  "codigo_disciplina_cursada": "SMA0801",
  "nome_disciplina_cursada": "Calculo I",
  "parecer_departamento": "Aprovado"
}
```

## Campos por disciplina

| Campo | Obrigatorio | Descricao |
| --- | --- | --- |
| `codigo_disciplina_requerida` | Sim | Codigo da disciplina que sera aproveitada no curso atual. |
| `nome_disciplina_requerida` | Nao | Nome usado como fallback quando nao houver retorno do Replicado. |
| `codigo_disciplina_cursada` | Sim | Codigo da disciplina cursada aceita como equivalente. |
| `nome_disciplina_cursada` | Nao | Nome usado como fallback quando houver apenas uma disciplina cursada no conjunto. |
| `periodo` | Nao | Campo aceito no JSON, mas nao persistido como semestre. |
| `parecer_departamento` | Nao | Campo aceito no JSON, mas nao persistido pelo importador atual. |
| `numero_reuniao` | Nao | Numero da reuniao salvo em `aproveitamentos.numero_reuniao`. |
| `data_reuniao` | Nao | Data da reuniao salva em `aproveitamentos.data_reuniao`. |
| `observacoes` | Nao | Observacoes salvas em `aproveitamentos.observacoes`. |

O campo `codigo_disciplina_cursada` pode representar um conjunto com ate tres
disciplinas. O script separa codigos usando `+`, `e`, `ou`, `/`, `,` ou `;`.

Exemplo:

```json
{
  "codigo_disciplina_requerida": "SCC0210",
  "codigo_disciplina_cursada": "SCC0201 + SCC0202"
}
```

## Campos administrativos globais

O primeiro objeto do JSON, alem de `curso`, pode conter valores globais para:

- `numero_reuniao`
- `data_reuniao`
- `observacoes`

Esses valores globais sao aplicados a todas as disciplinas importadas daquele
arquivo.

```json
{
  "curso": "Engenharia de Producao (18084/0)",
  "numero_reuniao": 100,
  "data_reuniao": "10/02/2026",
  "observacoes": "TABELA DE DISPENSAS DE DISCIPLINAS - JUNHO de 2026 Departamento de Matematica ICMC-USP"
}
```

Cada disciplina tambem pode informar esses mesmos campos. Quando isso acontece,
o valor local da disciplina tem prioridade sobre o valor global.

A regra e aplicada campo a campo:

- se a disciplina tem `numero_reuniao`, usa o valor local;
- se nao tem `numero_reuniao`, usa o valor global, se existir;
- o mesmo vale para `data_reuniao` e `observacoes`;
- se um campo nao existir nem no global nem na disciplina, ele nao e enviado ao banco.

Com isso, arquivos antigos que nao possuem esses campos continuam funcionando.
Tambem e valido informar apenas um ou dois desses campos, tanto no objeto global
quanto nas disciplinas.

## Formatos de data aceitos

O campo `data_reuniao` aceita:

- `dd/mm/aaaa`, por exemplo `10/02/2026`;
- `aaaa-mm-dd`, por exemplo `2026-02-10`.

Antes de salvar, o script normaliza a data para o formato aceito pela coluna
`date` do banco.

## Exemplo completo

```json
[
  {
    "curso": "Engenharia de Producao (18084/0)",
    "numero_reuniao": 100,
    "data_reuniao": "10/02/2026",
    "observacoes": "TABELA DE DISPENSAS DE DISCIPLINAS - JUNHO de 2026 Departamento de Matematica ICMC-USP"
  },
  {
    "periodo": " ",
    "codigo_disciplina_requerida": "SMA0301",
    "nome_disciplina_requerida": "Calculo I",
    "codigo_disciplina_cursada": "SMA0801",
    "nome_disciplina_cursada": "Calculo I",
    "parecer_departamento": "Aprovado"
  },
  {
    "periodo": " ",
    "codigo_disciplina_requerida": "SMA0301",
    "nome_disciplina_requerida": "Calculo I",
    "codigo_disciplina_cursada": "SMA0501",
    "nome_disciplina_cursada": "Calculo I",
    "parecer_departamento": "Aprovado",
    "numero_reuniao": 101,
    "data_reuniao": "11/02/2026",
    "observacoes": "TABELA DE DISPENSAS DE DISCIPLINAS ICMC-USP"
  }
]
```

Nesse exemplo, a disciplina `SMA0801` usa os campos administrativos globais. A
disciplina `SMA0501` usa os valores locais informados no proprio objeto.

## Validacoes principais

O script ignora o registro e continua a importacao quando encontra problemas
como:

- JSON invalido;
- primeiro item sem curso;
- curso fora do formato `Nome do Curso (codcur/codhab)`;
- disciplina requerida sem codigo;
- codigo de disciplina com mais de sete caracteres;
- conjunto sem disciplina cursada valida;
- conjunto com mais de tres disciplinas cursadas;
- `data_reuniao` fora dos formatos aceitos.
