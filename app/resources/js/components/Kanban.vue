<template>
  <div class="p-4">
    <div class="flex gap-2 mb-4">
      <button @click="setLanguage('pt')" :class="{ 'font-bold': idioma === 'pt' }">
        PT
      </button>
      <button @click="setLanguage('en')" :class="{ 'font-bold': idioma === 'en' }">
        EN
      </button>
    </div>
    <!-- Título e descrição -->
    <h2 class="text-xl font-semibold tracking-tight">{{ $t("kanban.title") }}</h2>
    <p class="text-sm text-muted-foreground">{{ $t("kanban.description") }}</p>
    <br />
    <!-- Botão para abrir formulário -->
    <button
      class="px-4 py-2 bg-blue-600 text-white"
      @click="cadastrar_tarefa = !cadastrar_tarefa"
    >
      {{ $t("kanban.buttons.cadastrar") }}
    </button>
    <br /><br />
    <!-- Formulário -->
    <form
      @submit.prevent="criarTarefa"
      class="mb-4 grid grid-cols-1 md:grid-cols-2 gap-4"
      v-show="cadastrar_tarefa"
    >
      <!-- Título -->
      <div>
        <label
          for="titulo"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300"
        >
          {{ $t("kanban.form.titulo.label") }}
        </label>
        <input
          id="titulo"
          v-model="novaTarefa.titulo"
          type="text"
          :placeholder="$t('kanban.form.titulo.placeholder')"
          class="mt-1 block w-full p-2 border rounded dark:bg-gray-800 dark:text-white"
          required
        />
      </div>
      <!-- Descrição -->
      <div>
        <label
          for="descricao"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300"
        >
          {{ $t("kanban.form.descricao.label") }}
        </label>
        <input
          id="descricao"
          v-model="novaTarefa.descricao"
          type="text"
          :placeholder="$t('kanban.form.descricao.placeholder')"
          class="mt-1 block w-full p-2 border rounded dark:bg-gray-800 dark:text-white"
        />
      </div>
      <!-- Data de vencimento -->
      <div>
        <label
          for="data_vencimento"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300"
        >
          {{ $t("kanban.form.data_vencimento.label") }}
        </label>
        <input
          id="data_vencimento"
          v-model="novaTarefa.data_vencimento"
          type="date"
          class="mt-1 block w-full p-2 border rounded dark:bg-gray-800 dark:text-white"
        />
      </div>
      <!-- Status -->
      <div>
        <label
          for="status"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300"
        >
          {{ $t("kanban.form.status.label") }}
        </label>
        <select
          id="status"
          v-model="novaTarefa.status"
          class="mt-1 block w-full p-2 border rounded dark:bg-gray-800 dark:text-white"
        >
          <option value="todo">{{ $t("kanban.form.status.options.todo") }}</option>
          <option value="doing">{{ $t("kanban.form.status.options.doing") }}</option>
          <option value="done">{{ $t("kanban.form.status.options.done") }}</option>
        </select>
      </div>
      <!-- Prioridade -->
      <div>
        <label
          for="prioridade"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300"
        >
          {{ $t("kanban.form.prioridade.label") }}
        </label>
        <select
          id="prioridade"
          v-model="novaTarefa.prioridade"
          class="mt-1 block w-full p-2 border rounded dark:bg-gray-800 dark:text-white"
        >
          <option value="baixa">{{ $t("kanban.form.prioridade.options.baixa") }}</option>
          <option value="media">{{ $t("kanban.form.prioridade.options.media") }}</option>
          <option value="alta">{{ $t("kanban.form.prioridade.options.alta") }}</option>
        </select>
      </div>
      <!-- Responsável -->
      <div>
        <label
          for="usuario_responsavel"
          class="block text-sm font-medium text-gray-700 dark:text-gray-300"
        >
          {{ $t("kanban.form.usuario_responsavel.label") }}
        </label>
        <select
          id="usuario_responsavel"
          v-model="novaTarefa.usuario_responsavel"
          class="mt-1 block w-full p-2 border rounded dark:bg-gray-800 dark:text-white"
        >
          <option
            v-for="(user, user_i) in options_users"
            :key="user_i"
            :value="user.value"
          >
            {{ user.text }}
          </option>
        </select>
      </div>

      <!-- Botão adicionar -->
      <div class="md:col-span-2">
        <button type="submit" class="w-full px-4 py-2 bg-blue-600 text-white rounded">
          {{ $t("kanban.buttons.adicionar") }}
        </button>
      </div>
    </form>

    <!-- Kanban -->
    <div class="kanban grid grid-cols-1 md:grid-cols-3 gap-4">
      <!-- Colunas -->
      <div
        v-for="col in columns"
        :key="col.key"
        class="flex flex-col rounded-lg bg-gray-100 dark:bg-gray-800 shadow-md"
      >
        <!-- Cabeçalho da coluna -->
        <div class="p-3 border-b border-gray-300 dark:border-gray-700">
          <h2 class="font-semibold text-gray-700 dark:text-gray-200">
            {{ $t(`kanban.kanban_columns.${col.key}`) }}
          </h2>
        </div>

        <!-- Cards -->
        <draggable
          v-model="col.tasks"
          group="tarefas"
          item-key="id"
          class="flex-1 p-3 space-y-3"
          @change="onDrop(col.key, $event)"
        >
          <template #item="{ element }">
            <div
              class="group relative rounded-lg bg-white dark:bg-gray-700 shadow hover:shadow-lg transition p-3 cursor-pointer"
            >
              <h3 class="font-medium text-gray-800 dark:text-white">
                {{ element.titulo }}
              </h3>
              <p class="text-sm text-gray-600 dark:text-gray-300">
                {{ element.descricao }}
              </p>

              <!-- Detalhes -->
              <div class="mt-3 space-y-1 text-xs text-gray-500 dark:text-gray-300">
                <p>
                  {{ $t("kanban.labels.data_vencimento") }}: {{ element.data_vencimento }}
                </p>
                <p>{{ $t("kanban.labels.prioridade") }}: {{ element.prioridade }}</p>
                <p>
                  {{ $t("kanban.labels.responsavel") }}: {{ element.usuario_responsavel }}
                </p>
              </div>

              <!-- Botão deletar no hover -->
              <button
                @click="deletarTarefa(element)"
                class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition text-gray-400 hover:text-red-500"
              >
                🗑
              </button>
              <select
                v-model="element.status"
                @change="handleSelect(element, $event)"
                class="dark:bg-gray-800 dark:text-white"
              >
                <option value="todo">{{ $t("kanban.form.status.options.todo") }}</option>
                <option value="doing">
                  {{ $t("kanban.form.status.options.doing") }}
                </option>
                <option value="done">{{ $t("kanban.form.status.options.done") }}</option>
              </select>
            </div>
          </template>
        </draggable>
      </div>
    </div>
  </div>
</template>

<script>
import draggable from "vuedraggable";
import axios from "axios";

export default {
  name: "Kanban",
  components: { draggable },
  data() {
    return {
      cadastrar_tarefa: false,
      novaTarefa: {
        titulo: "",
        descricao: "",
        data_vencimento: null,
        status: "todo",
        prioridade: "baixa",
        usuario_responsavel: null,
      },
      columns: [
        { key: "todo", title: "Pendente", tasks: [] },
        { key: "doing", title: "Em Progresso", tasks: [] },
        { key: "done", title: "Concluído", tasks: [] },
      ],
      options_users: [],
      idioma: this.$i18n.locale, // para controlar o select
    };
  },
  mounted() {
    this.loadTasks();
    this.loadOptionUser();
  },
  methods: {
    setLanguage(lang) {
      this.$i18n.locale = lang;
      this.idioma = lang;
    },
    async loadTasks() {
      const { data } = await axios.get("/tarefas");
      this.columns.forEach((col) => {
        col.tasks = data.filter((t) => t.status === col.key);
      });
    },
    async loadOptionUser() {
      this.options_users = (await axios.get("/tarefas/option_users")).data;
    },
    async criarTarefa() {
      const { data } = await axios.post("/tarefas", this.novaTarefa);

      // joga a tarefa direto na coluna escolhida
      this.columns.find((c) => c.key === this.novaTarefa.status).tasks.push(data);

      // limpa o form
      this.novaTarefa.titulo = "";
      this.novaTarefa.descricao = "";
      this.novaTarefa.data_vencimento = null;
      this.novaTarefa.status = "todo";
      this.novaTarefa.prioridade = "baixa";
      this.novaTarefa.usuario_responsavel = null;
    },
    async onDrop(newStatus, event) {
      let task = null;

      if (event.added) {
        task = event.added.element;
      } else if (event.moved) {
        task = event.moved.element;
      }

      if (!task) return;

      try {
        await axios.patch(`/tarefas/${task.id}/status`, { status: newStatus });
        this.loadTasks();
      } catch (error) {
        console.error("Erro ao atualizar status:", error);
      }
    },
    async handleSelect(task, event) {
      const newStatus = event.target.value;

      try {
        await axios.patch(`/tarefas/${task.id}/status`, { status: newStatus });
        this.loadTasks();
      } catch (error) {
        console.error("Erro ao atualizar status:", error);
      }
    },
    async deletarTarefa(task) {
      if (!confirm(`Tem certeza que deseja excluir a tarefa "${task.titulo}"?`)) return;

      try {
        await axios.delete(`/tarefas/delete/${task.id}`);

        this.loadTasks();
      } catch (error) {
        console.error("Erro ao deletar tarefa:", error);
        alert("Erro ao deletar a tarefa.");
      }
    },
  },
};
</script>
