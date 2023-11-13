<template>
  9 items Name Description content1 description of content1 content2 description of
  content2 content3 description of content3 content4 description of content4 content5
  description of content5 content6 description of content6 content7 description of
  content7 content8 description of content8 content10 description of content10 1 item Name
  content9 Table Transfer Customize render list with Table component. JS
  <template>
    <div>
      <Transfer
        v-model:target-keys="targetKeys"
        :data-source="mockData"
        :disabled="disabled"
        :show-search="showSearch"
        :filter-option="(inputValue, item) => item.title.indexOf(inputValue) !== -1"
        :show-select-all="false"
        @change="onChange"
      >
        <template
          #children="{
            direction,
            filteredItems,
            selectedKeys,
            disabled: listDisabled,
            onItemSelectAll,
            onItemSelect,
          }"
        >
          <a-table
            :row-selection="
              getRowSelection({
                disabled: listDisabled,
                selectedKeys,
                onItemSelectAll,
                onItemSelect,
              })
            "
            :columns="direction === 'left' ? leftColumns : rightColumns"
            :data-source="filteredItems"
            size="small"
            :style="{ pointerEvents: listDisabled ? 'none' : null }"
            :custom-row="
              ({ key, disabled: itemDisabled }) => ({
                onClick: () => {
                  if (itemDisabled || listDisabled) return;
                  onItemSelect(key, !selectedKeys.includes(key));
                },
              })
            "
          />
        </template>
      </Transfer>
    </div>
  </template>
</template>

<script>
import { ref, defineComponent, inject, reactive, toRefs } from "vue";

import { message, Transfer } from "ant-design-vue";

export default defineComponent({
  setup() {
    const mockData = [];
    for (let i = 0; i < 10; i++) {
      mockData.push({
        key: i.toString(),
        title: `content${i + 1}`,
        description: `description of content${i + 1}`,
        disabled: i % 4 === 0,
      });
    }
    const originTargetKeys = mockData
      .filter((item) => +item.key % 3 > 1)
      .map((item) => item.key);
    const leftTableColumns = [
      {
        dataIndex: "title",
        title: "Name",
      },
      {
        dataIndex: "description",
        title: "Description",
      },
    ];
    const rightTableColumns = [
      {
        dataIndex: "title",
        title: "Name",
      },
    ];
    const targetKeys = ref(originTargetKeys);
    const disabled = ref(false);
    const showSearch = ref(false);
    const leftColumns = ref(leftTableColumns);
    const rightColumns = ref(rightTableColumns);
    const onChange = (nextTargetKeys) => {
      console.log("nextTargetKeys", nextTargetKeys);
    };
    const getRowSelection = ({
      disabled,
      selectedKeys,
      onItemSelectAll,
      onItemSelect,
    }) => {
      return {
        getCheckboxProps: (item) => ({
          disabled: disabled || item.disabled,
        }),
        onSelectAll(selected, selectedRows) {
          const treeSelectedKeys = selectedRows
            .filter((item) => !item.disabled)
            .map(({ key }) => key);
          onItemSelectAll(treeSelectedKeys, selected);
        },
        onSelect({ key }, selected) {
          onItemSelect(key, selected);
        },
        selectedRowKeys: selectedKeys,
      };
    };
    return {};
  },
  components: { Transfer },
});
</script>

<style></style>
