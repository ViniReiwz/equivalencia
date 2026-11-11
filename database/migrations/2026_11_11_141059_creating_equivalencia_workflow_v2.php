<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Uspdev\Workflow\Models\WorkflowDefinition;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        
        $workflowDefReq = Request::create('/createequivalenciawf', 'POST', [
            'name' => 'equivalencia',
            'description' => 'Workflow para processo de aproveitamento de estudos',
            'version' => 1,
            'definition' => '
            {
                "name": "equivalencia",
                "label": "Solicitação de equivalência de disciplinas",
                "description": "Workflow de equivalência de disciplinas",
                "initial_places": [
                    "aluno_inicio"
                ],
                "roles": [
                    {
                        "name": "aluno",
                        "label": "Aluno"
                    },
                    {
                        "name": "svgrad",
                        "label": "Serviço de Graduação"
                    },
                    {
                        "name": "depto",
                        "label": "Departamento"
                    },
                    {
                        "name": "docente",
                        "label": "Docente"
                    },
                    {
                        "name": "conselho",
                        "label": "Conselho do Departamento"
                    },
                    {
                        "name": "coc",
                        "label": "CoC"
                    }
                ],
                "places": [
                    {
                        "name": "aluno_inicio",
                        "label": "Solicitação inicial",
                        "roles": [
                            "aluno"
                        ]
                    },
                    {
                        "name": "svgrad_conferencia",
                        "label": "Conferência de documentos",
                        "roles": [
                            "svgrad"
                        ]
                    },
                    {
                        "name": "depto_indica_docente",
                        "label": "Indicação de docente",
                        "roles": [
                            "depto"
                        ]
                    },
                    {
                        "name": "docente_emite_parecer",
                        "label": "Emissão de parecer",
                        "roles": [
                            "docente"
                        ]
                    },
                    {
                        "name": "depto_envia_apreciacao",
                        "label": "Apreciação departamental",
                        "roles": [
                            "depto"
                        ]
                    },
                    {
                        "name": "conselho_delibera",
                        "label": "Deliberação do conselho",
                        "roles": [
                            "conselho"
                        ]
                    },
                    {
                        "name": "depto_retorno_apreciacao",
                        "label": "Retorno da apreciação",
                        "roles": [
                            "depto"
                        ]
                    },
                    {
                        "name": "svgrad_apreciacao_coc",
                        "label": "Apreciação da CoC",
                        "roles": [
                            "svgrad"
                        ]
                    },
                    {
                        "name": "coc_delibera_solicitacao",
                        "label": "Deliberação da CoC",
                        "roles": [
                            "coc"
                        ]
                    },
                    {
                        "name": "svgrad_recebe_deliberacao",
                        "label": "Recebimento da deliberação",
                        "roles": [
                            "svgrad"
                        ]
                    },
                    {
                        "name": "svgrad_cadastra_aprovadas",
                        "label": "Cadastro das aprovadas",
                        "roles": [
                            "svgrad"
                        ]
                    },
                    {
                        "name": "aluno_preavaliacao",
                        "label": "Avaliação necessária",
                        "roles": [
                            "aluno"
                        ]
                    },
                    {
                        "name": "aluno_avaliacao",
                        "label": "Avaliação",
                        "roles": [
                            "aluno"
                        ]
                    },
                    {
                        "name": "docente_avaliacao",
                        "label": "Avaliação docente",
                        "roles": [
                            "docente"
                        ]
                    },
                    {
                        "name": "docente_delibera",
                        "label": "Deliberação docente",
                        "roles": [
                            "docente"
                        ]
                    },
                    {
                        "name": "svgrad_finaliza",
                        "label": "Finalização",
                        "roles": [
                            "svgrad"
                        ]
                    },
                    {
                        "name": "aluno_fim",
                        "label": "Confirmação de finalização",
                        "roles": [
                            "aluno"
                        ]
                    }
                ],
                "transitions": [
                    {
                        "name": "tr_inicio_conferencia",
                        "label": "Enviar para o Serviço de Graduação",
                        "from": "aluno_inicio",
                        "tos": [
                            "svgrad_conferencia"
                        ],
                        "form": false
                    },
                    {
                        "name": "tr_conferencia_inicio",
                        "label": "Retornar para o aluno",
                        "from": "svgrad_conferencia",
                        "tos": [
                            "aluno_inicio"
                        ]
                    },
                    {
                        "name": "tr_conferencia_indica",
                        "label": "Enviar para o Departamento",
                        "from": "svgrad_conferencia",
                        "tos": [
                            "depto_indica_docente"
                        ]
                    },
                    {
                        "name": "tr_indica_parecerista",
                        "label": "Indicar parecerista",
                        "from": "depto_indica_docente",
                        "tos": [
                            "docente_emite_parecer"
                        ]
                    },
                    {
                        "name": "tr_emite_parecer",
                        "label": "Emitir parecer",
                        "from": "docente_emite_parecer",
                        "tos": [
                            "depto_envia_apreciacao"
                        ]
                    },
                    {
                        "name": "tr_envia_apreciacao",
                        "label": "Enviar para o Conselho do Departamento",
                        "from": "depto_envia_apreciacao",
                        "tos": [
                            "conselho_delibera"
                        ]
                    },
                    {
                        "name": "tr_delibera_parecer",
                        "label": "Enviar deliberação",
                        "from": "conselho_delibera",
                        "tos": [
                            "depto_retorno_apreciacao"
                        ]
                    },
                    {
                        "name": "tr_retorna_apreciacao",
                        "label": "Enviar apreciação para a CoC",
                        "from": "depto_retorno_apreciacao",
                        "tos": [
                            "svgrad_apreciacao_coc"
                        ]
                    },
                    {
                        "name": "tr_conferencia_apreciacao",
                        "label": "Solicitar envio à CoC",
                        "from": "svgrad_conferencia",
                        "tos": [
                            "svgrad_apreciacao_coc"
                        ]
                    },
                    {
                        "name": "tr_solicitacao_coc",
                        "label": "Enviar solicitação para a CoC",
                        "from": "svgrad_apreciacao_coc",
                        "tos": [
                            "coc_delibera_solicitacao"
                        ]
                    },
                    {
                        "name": "tr_envia_deliberacao",
                        "label": "Enviar deliberação para o Serviço de Graduação",
                        "from": "coc_delibera_solicitacao",
                        "tos": [
                            "svgrad_recebe_deliberacao"
                        ]
                    },
                    {
                        "name": "tr_conferencia_deliberacao",
                        "label": "Enviar resposta da CoC para cadastro",
                        "from": "svgrad_conferencia",
                        "tos": [
                            "svgrad_recebe_deliberacao"
                        ]
                    },
                    {
                        "name": "tr_enviar_condicional",
                        "label": "Cadastrar condicional",
                        "from": "svgrad_recebe_deliberacao",
                        "tos": [
                            "aluno_preavaliacao",
                            "docente_avaliacao"
                        ]
                    },
                    {
                        "name": "tr_enviar_aprovada",
                        "label": "Cadastrar aprovada",
                        "from": "svgrad_recebe_deliberacao",
                        "tos": [
                            "svgrad_cadastra_aprovadas"
                        ]
                    },
                    {
                        "name": "tr_enviar_negada",
                        "label": "Cadastrar negativa",
                        "from": "svgrad_recebe_deliberacao",
                        "tos": [
                            "aluno_fim"
                        ]
                    },
                    {
                        "name": "tr_cadastrar_solicitacoes",
                        "label": "Finalizar cadastro de solicitações",
                        "from": "svgrad_cadastra_aprovadas",
                        "tos": [
                            "svgrad_finaliza"
                        ]
                    },
                    {
                        "name": "tr_enviar_avaliacao",
                        "label": "Realizar avaliação",
                        "from": "docente_avaliacao",
                        "tos": [
                            "aluno_avaliacao"
                        ]
                    },
                    {
                        "name": "tr_realizar_avaliacao",
                        "label": "Realizar avaliação",
                        "from": "aluno_preavaliacao",
                        "tos": [
                            "aluno_avaliacao"
                        ]
                    },
                    {
                        "name": "tr_submeter_avaliacao",
                        "label": "Submeter avaliação para docente",
                        "from": "aluno_avaliacao",
                        "tos": [
                            "docente_delibera"
                        ]
                    },
                    {
                        "name": "tr_delibera_avaliacao",
                        "label": "Deliberar sobre a avaliação",
                        "from": "docente_delibera",
                        "tos": [
                            "svgrad_recebe_deliberacao"
                        ]
                    },
                    {
                        "name": "tr_finalizar_enviar",
                        "label": "Finalizar processo",
                        "from": "svgrad_finaliza",
                        "tos": [
                            "aluno_fim"
                        ]
                    }
                ]
            }']);

        $workflowDef = WorkflowDefinition::storeDefinition($workflowDefReq);
        $workflowDef->publish();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
